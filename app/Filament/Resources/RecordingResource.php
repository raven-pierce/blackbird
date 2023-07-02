<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecordingResource\Pages\CreateRecording;
use App\Filament\Resources\RecordingResource\Pages\EditRecording;
use App\Filament\Resources\RecordingResource\Pages\ListRecordings;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Recording;
use App\Models\Section;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class RecordingResource extends Resource
{
    protected static ?string $model = Recording::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Academics';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('lecture.section.course_id')
                    ->label('Course')
                    ->searchable()
                    ->options(Course::all()->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(function (\Closure $set) {
                        $set('lecture.section_id', null);
                        $set('lecture_id', null);
                    })
                    ->dehydrated(false)
                    ->required(),
                Select::make('lecture.section_id')
                    ->label('Section Code')
                    ->searchable()
                    ->options(function (\Closure $get) {
                        $course = Course::find($get('lecture.section.course_id'));

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
                        $section = Section::find($get('lecture.section_id'));

                        if (! $section) {
                            return $query;
                        }

                        return $query->whereBelongsTo($section);
                    })
                    ->preload()
                    ->afterStateHydrated(function (\Closure $set, $state, $context) {
                        if ($context === 'edit') {
                            $set('lecture.section.course_id', Lecture::find($state)->section->course_id);
                            $set('lecture.section_id', Lecture::find($state)->section_id);
                        }
                    })
                    ->required(),
                TextInput::make('azure_item_id')
                    ->label('Azure Item ID')
                    ->required(),
                TextInput::make('file_name')
                    ->label('File Name')
                    ->required(),
                TextInput::make('file_path')
                    ->label('File Path')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lecture.section.course.name')->label('Course')->sortable(),
                TextColumn::make('lecture.section.code')->label('Section')->sortable(),
                TextColumn::make('file_name')->label('File Name')->limit(25)->sortable(),
                TextColumn::make('lecture.start_date')->label('Recording Date')->dateTime('l, d F Y')->sortable(),
            ])
            ->defaultSort('lecture.start_date', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                Action::make('downloadRecording')
                    ->label('Download')
                    ->icon('heroicon-s-download')
                    ->action(function (Recording $record) {
                        $signedUrl = Storage::disk('do')->temporaryUrl($record->file_path, now()->addHour());
                        cache(["{$record->id}_url", $signedUrl], now()->addHour());

                        return Redirect::to($signedUrl);
                    })
                    ->visible(fn (Recording $record): bool => auth()->user()->enrollments()->where('section_id', $record->lecture->section->id)->attendedLecture($record->lecture)->exists() || auth()->user()->hasAnyRole(['icarus', 'tutor'])),
                Action::make('requestRecording')
                    ->label('Request')
                    ->icon('heroicon-s-video-camera')
                    ->visible(fn (Recording $record): bool => auth()->user()->enrollments()->where('section_id', $record->lecture->section->id)->attendedLecture($record->lecture)->doesntExist() && ! auth()->user()->hasAnyRole(['icarus', 'tutor']))
                    ->requiresConfirmation()
                    ->modalSubheading('Once you request this recording, you will have a full lecture\'s cost added to your pending invoice. Would you like to continue?')
                    ->modalButton('Request')
                    ->action(function (Recording $record) {
                        $record->lecture->attendances()->create([
                            'enrollment_id' => auth()->user()->enrollments()->where('section_id', $record->lecture->section->id)->first()->id,
                            'join_date' => $record->lecture->start_date,
                            'leave_date' => $record->lecture->end_date,
                            'duration' => $record->lecture->duration,
                        ]);

                        Notification::make()
                            ->title('Recording Granted')
                            ->body('You may access it through this page.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->label('Delete Selected'),
                RestoreBulkAction::make()->label('Restore Selected'),
                ForceDeleteBulkAction::make()->label('Force Delete Selected'),
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
            'index' => ListRecordings::route('/'),
            'create' => CreateRecording::route('/create'),
            'edit' => EditRecording::route('/{record}/edit'),
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
