<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages\CreateAttendance;
use App\Filament\Resources\AttendanceResource\Pages\EditAttendance;
use App\Filament\Resources\AttendanceResource\Pages\ListAttendances;
use App\Imports\Attendances;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lecture;
use App\Models\Section;
use App\Models\User;
use Carbon\CarbonInterval;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-alt';

    protected static ?string $navigationGroup = 'Academics';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Enrollment Information')
                    ->schema([
                        Select::make('enrollment.section.course_id')
                            ->label('Course')
                            ->searchable()
                            ->options(Course::all()->pluck('name', 'id'))
                            ->reactive()
                            ->afterStateUpdated(function (\Closure $set) {
                                $set('enrollment.section_id', null);
                                $set('lecture_id', null);
                            })
                            ->dehydrated(false)
                            ->required(),
                        Select::make('enrollment.section_id')
                            ->label('Section Code')
                            ->searchable()
                            ->options(function (\Closure $get) {
                                $course = Course::find($get('enrollment.section.course_id'));

                                if (! $course) {
                                    return Section::all()->pluck('code', 'id');
                                }

                                return $course->sections->pluck('code', 'id');
                            })
                            ->afterStateUpdated(fn (callable $set) => $set('lecture_id', null))
                            ->reactive()
                            ->required(),
                        Select::make('lecture_id')
                            ->label('Lecture')
                            ->searchable()
                            ->relationship('lecture', 'start_date', function ($query, \Closure $get) {
                                $section = Section::find($get('enrollment.section_id'));

                                if (! $section) {
                                    return $query;
                                }

                                return $query->whereBelongsTo($section);
                            })
                            ->preload()
                            ->afterStateHydrated(function (\Closure $set, $state, $context) {
                                if ($context === 'edit') {
                                    $set('enrollment.section.course_id', Lecture::find($state)->section->course_id);
                                    $set('enrollment.section_id', Lecture::find($state)->section_id);
                                }
                            })
                            ->required(),
                        Select::make('enrollment_id')
                            ->label('Student')
                            ->searchable()
                            ->relationship('enrollment', 'id', function (Builder $query, \Closure $get) {
                                $section = Section::find($get('enrollment.section_id'));

                                if (! $section) {
                                    return $query;
                                }

                                return $query->whereBelongsTo($section);
                            })
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->student->name)
                            ->required(),
                    ]),
                Fieldset::make('Attendances Information')
                    ->schema([
                        DateTimePicker::make('join_date')
                            ->label('Join Time')
                            ->displayFormat('d F Y, h:i A')
                            ->withoutSeconds()
                            ->required(),
                        DateTimePicker::make('leave_date')
                            ->label('Leave Time')
                            ->displayFormat('d F Y, h:i A')
                            ->withoutSeconds()
                            ->required(),
                        TextInput::make('duration')
                            ->label('Duration in Minutes')
                            ->numeric()
                            ->required(),
                        TextInput::make('invoice_id')
                            ->label('Invoice ID'),
                        Toggle::make('paid')
                            ->label('Attendances Paid?')
                            ->inline(false)
                            ->onIcon('heroicon-s-cash')
                            ->offIcon('heroicon-s-book-open')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('enrollment.section.course.name')->label('Course')->sortable(),
                TextColumn::make('enrollment.section.code')->label('Section Code')->sortable(),
                TextColumn::make('enrollment.student.name')->label('Student')->sortable(),
                TextColumn::make('lecture.start_date')->label('Date')->dateTime('l, d F Y')->sortable(),
                BooleanColumn::make('paid')->label('Paid?')->sortable()->trueIcon('heroicon-s-badge-check')->falseIcon('heroicon-s-x-circle'),
            ])
            ->defaultSort('lecture.start_date', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->label('Delete Selected'),
                RestoreBulkAction::make()->label('Restore Selected'),
                ForceDeleteBulkAction::make()->label('Force Delete Selected'),
            ])
            ->headerActions([
                Action::make('import')
                    ->label('Import')
                    ->action(function (array $data) {
                        $lecture = Lecture::find($data['lecture_id']);
                        $attendees = Excel::toCollection(new Attendances(), $data['xlsx'])->flatten(1);

                        foreach ($attendees as $attendee) {
                            $user = User::query()->where('email', $attendee['email'])->first();
                            $duration = floor(CarbonInterval::fromString($attendee['duration'])->totalMinutes);

                            if (! $user) {
                                Notification::make()
                                    ->title('User Not Found')
                                    ->body("A user with the email {$attendee['email']} could not be found.")
                                    ->danger()
                                    ->send();

                                continue;
                            }

                            $enrollment = Enrollment::query()->whereBelongsTo($user, 'student')->whereBelongsTo($lecture->section)->first();

                            if (! $enrollment) {
                                Notification::make()
                                    ->title('Enrollment Not Found')
                                    ->body("An enrollment for the user {$user->name} could not be found in this section.")
                                    ->danger()
                                    ->send();

                                continue;
                            }

                            $lecture->attendances()->updateOrCreate(['enrollment_id' => $enrollment->id], [
                                'join_date' => $lecture->start_date,
                                'leave_date' => $lecture->start_date->copy()->addMinutes($duration),
                                'duration' => $duration,
                            ]);
                        }

                        Storage::disk('local')->delete($data['xlsx']);

                        Notification::make()
                            ->title('Attendances Imported')
                            ->success()
                            ->send();
                    })
                    ->form([
                        Fieldset::make('Enrollment Information')
                            ->schema([
                                Select::make('enrollment.section.course_id')
                                    ->label('Course')
                                    ->searchable()
                                    ->options(Course::all()->pluck('name', 'id'))
                                    ->reactive()
                                    ->afterStateUpdated(function (\Closure $set) {
                                        $set('enrollment.section_id', null);
                                        $set('lecture_id', null);
                                    })
                                    ->dehydrated(false)
                                    ->required(),
                                Select::make('enrollment.section_id')
                                    ->label('Section Code')
                                    ->searchable()
                                    ->options(function (\Closure $get) {
                                        $course = Course::find($get('enrollment.section.course_id'));

                                        if (! $course) {
                                            return Section::all()->pluck('code', 'id');
                                        }

                                        return $course->sections->pluck('code', 'id');
                                    })
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set) => $set('lecture_id', null))
                                    ->required(),
                                Select::make('lecture_id')
                                    ->label('Lecture')
                                    ->searchable()
                                    ->relationship('lecture', 'start_date', function ($query, \Closure $get) {
                                        $section = Section::find($get('enrollment.section_id'));

                                        if (! $section) {
                                            return $query;
                                        }

                                        return $query->whereBelongsTo($section);
                                    })
                                    ->preload()
                                    ->afterStateHydrated(function (\Closure $set, $state, $context) {
                                        if ($context === 'edit') {
                                            $set('enrollment.section.course_id', Lecture::find($state)->section->course_id);
                                            $set('enrollment.section_id', Lecture::find($state)->section_id);
                                        }
                                    })
                                    ->required(),
                            ]),
                        FileUpload::make('xlsx')->label('Attendances Sheet')->disk('local')->directory('attendances')->required(),
                    ])
                    ->visible(fn () => auth()->user()->hasRole('icarus')),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendances::route('/'),
            'create' => CreateAttendance::route('/create'),
            'edit' => EditAttendance::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('icarus')) {
            return parent::getEloquentQuery()
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]);
        }

        if (auth()->user()->hasRole('tutor')) {
            return parent::getEloquentQuery()
                ->taughtBy(auth()->user())
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]);
        }

        return parent::getEloquentQuery()
            ->studentEnrolled(auth()->user())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
