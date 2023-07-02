<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubmissionResource\Pages\CreateSubmission;
use App\Filament\Resources\SubmissionResource\Pages\EditSubmission;
use App\Filament\Resources\SubmissionResource\Pages\ListSubmissions;
use App\Imports\Submissions;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    protected static ?string $navigationGroup = 'Academics';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('assessment.section.course_id')
                    ->label('Course')
                    ->searchable()
                    ->options(Course::all()->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(function (\Closure $set) {
                        $set('assessment.section_id', null);
                        $set('lecture_id', null);
                    })
                    ->dehydrated(false)
                    ->required(),
                Select::make('assessment.section_id')
                    ->label('Section Code')
                    ->searchable()
                    ->options(function (\Closure $get) {
                        $course = Course::find($get('assessment.section.course_id'));

                        if (! $course) {
                            return Section::all()->pluck('code', 'id');
                        }

                        return $course->sections->pluck('code', 'id');
                    })
                    ->afterStateUpdated(fn (callable $set) => $set('lecture_id', null))
                    ->reactive()
                    ->required(),
                Select::make('assessment_id')
                    ->label('Assessment')
                    ->searchable()
                    ->relationship('assessment', 'topic', function ($query, \Closure $get) {
                        $section = Section::find($get('assessment.section_id'));

                        if (! $section) {
                            return $query;
                        }

                        return $query->whereBelongsTo($section);
                    })
                    ->preload()
                    ->afterStateHydrated(function (\Closure $set, $state, $context) {
                        if ($context === 'edit') {
                            $set('assessment.section.course_id', Assessment::find($state)->section->course_id);
                            $set('assessment.section_id', Assessment::find($state)->section_id);
                        }
                    })
                    ->required(),
                Select::make('enrollment_id')
                    ->label('Student')
                    ->searchable()
                    ->relationship('enrollment', 'id', function (Builder $query, \Closure $get) {
                        $section = Section::find($get('assessment.section_id'));

                        if (! $section) {
                            return $query;
                        }

                        return $query->whereBelongsTo($section);
                    })
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->student->name)
                    ->required(),
                DateTimePicker::make('submission_date')->label('Submission Date')->required(),
                TextInput::make('score')->label('Score')->numeric()
                    ->maxValue(fn (callable $get) => $get('assessment_id') ? Assessment::find($get('assessment_id'))->max_score : 100)
                    ->suffix(fn (callable $get) => $get('assessment_id') ? ' / '.Assessment::find($get('assessment_id'))->max_score : ' / 100'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('assessment.section.course.name')->label('Course')->sortable(),
                TextColumn::make('assessment.section.code')->label('Section Code')->sortable(),
                TextColumn::make('assessment.type')->label('Type')->sortable(),
                TextColumn::make('assessment.topic')->label('Topic')->sortable(),
                TextColumn::make('enrollment.student.name')->label('Student')->sortable(),
                TextColumn::make('score')->label('Score')->suffix(fn (Submission $record) => " / {$record->assessment->max_score}")->sortable(),
                TextColumn::make('submission_date')->label('Submission Date')->dateTime('l, d F Y')->sortable(),
            ])
            ->defaultSort('submission_date', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make()->exports([
                    ExcelExport::make()->fromTable(),
                ]),
                DeleteBulkAction::make()->label('Delete Selected'),
                RestoreBulkAction::make()->label('Restore Selected'),
                ForceDeleteBulkAction::make()->label('Force Delete Selected'),
            ])
            ->headerActions([
                Action::make('importSubmissions')
                    ->label('Import')
                    ->action(function (array $data) {
                        $assessment = Assessment::find($data['assessment_id']);
                        $submissions = Excel::toCollection(new Submissions(), $data['xlsx'])->flatten(1);

                        foreach ($submissions as $submission) {
                            $user = User::query()->where('email', $submission['email'])->first();
                            $submission_date = Carbon::parse($submission['submission_date']);

                            if (! $user) {
                                Notification::make()
                                    ->title('User Not Found')
                                    ->body("A user with the email {$submission['email']} could not be found.")
                                    ->danger()
                                    ->send();

                                continue;
                            }

                            $enrollment = Enrollment::query()->whereBelongsTo($user, 'student')->whereBelongsTo($assessment->section)->first();

                            if (! $enrollment) {
                                Notification::make()
                                    ->title('Enrollment Not Found')
                                    ->body("An enrollment for the user {$user->name} could not be found in this section.")
                                    ->danger()
                                    ->send();

                                continue;
                            }

                            $assessment->submissions()->updateOrCreate(['enrollment_id' => $enrollment->id], [
                                'score' => $submission['score'],
                                'submission_date' => $submission_date,
                            ]);
                        }

                        Storage::disk('local')->delete($data['xlsx']);

                        Notification::make()
                            ->title('Submissions Imported')
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
                                        $set('assessment_id', null);
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
                                    ->afterStateUpdated(fn (callable $set) => $set('assessment_id', null))
                                    ->required(),
                                Select::make('assessment_id')
                                    ->label('Assessment')
                                    ->searchable()
                                    ->relationship('assessment', 'topic', function ($query, \Closure $get) {
                                        $section = Section::find($get('enrollment.section_id'));

                                        if (! $section) {
                                            return $query;
                                        }

                                        return $query->whereBelongsTo($section);
                                    })
                                    ->preload()
                                    ->afterStateHydrated(function (\Closure $set, $state, $context) {
                                        if ($context === 'edit') {
                                            $set('enrollment.section.course_id', Assessment::find($state)->section->course_id);
                                            $set('enrollment.section_id', Assessment::find($state)->section_id);
                                        }
                                    })
                                    ->required(),
                            ]),
                        FileUpload::make('xlsx')->label('Submissions Sheet')->disk('local')->directory('submissions')->required(),
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
            'index' => ListSubmissions::route('/'),
            'create' => CreateSubmission::route('/create'),
            'edit' => EditSubmission::route('/{record}/edit'),
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
