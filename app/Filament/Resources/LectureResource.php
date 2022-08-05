<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LectureResource\Pages\CreateLecture;
use App\Filament\Resources\LectureResource\Pages\EditLecture;
use App\Filament\Resources\LectureResource\Pages\ListLectures;
use App\Filament\Resources\LectureResource\RelationManagers\AttendancesRelationManager;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
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

class LectureResource extends Resource
{
    protected static ?string $model = Lecture::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Academics';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('section.course_id')
                    ->label('Course')
                    ->searchable()
                    ->options(Course::all()->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(fn(callable $set) => $set('section_id', null))
                    ->dehydrated(false)
                    ->required(),
                Select::make('section_id')
                    ->label('Section Code')
                    ->searchable()
                    ->relationship('section', 'code', function ($query, \Closure $get) {
                        $course = Course::find($get('section.course_id'));

                        if (!$course) {
                            return $query;
                        }

                        return $query->whereBelongsTo($course);
                    })
                    ->preload()
                    ->afterStateHydrated(function (\Closure $set, $state, $context) {
                        if ($context === 'edit') {
                            $set('section.course_id', Section::find($state)->course_id);
                        }
                    })
                    ->reactive()
                    ->required(),
                DateTimePicker::make('start_time')
                    ->label('Lecture Start')
                    ->displayFormat('l, d F Y h:i A')
                    ->withoutSeconds()
                    ->required(),
                DateTimePicker::make('end_time')
                    ->label('Lecture End')
                    ->displayFormat('l, d F Y h:i A')
                    ->withoutSeconds()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TODO: Sorting Double Nested Relationships
                TextColumn::make('section.course.name')->label('Course')->sortable(),
                TextColumn::make('section.code')->label('Section Code')->sortable(),
                TextColumn::make('start_time')->label('Lecture Start')->dateTime('l, d F Y h:i A')->sortable(),
                TextColumn::make('end_time')->label('Lecture End')->dateTime('l, d F Y h:i A')->sortable(),
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
            AttendancesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLectures::route('/'),
            'create' => CreateLecture::route('/create'),
            'edit' => EditLecture::route('/{record}/edit'),
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
