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
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecordingResource extends Resource
{
    protected static ?string $model = Recording::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Academics';

    protected static ?int $navigationSort = 6;

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
                    ->relationship('lecture', 'start_time', function ($query, \Closure $get) {
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
                TextInput::make('file_url')
                    ->label('File URL')
                    ->url()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lecture.section.course.name')->label('Course')->sortable(),
                TextColumn::make('lecture.section.code')->label('Section Code')->sortable(),
                TextColumn::make('lecture.start_time')->label('Recording Date')->dateTime('l, d F Y')->sortable(),
                TextColumn::make('created_at')->label('Uploaded')->since()->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
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
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
