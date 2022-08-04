<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SectionResource\Pages\CreateSection;
use App\Filament\Resources\SectionResource\Pages\EditSection;
use App\Filament\Resources\SectionResource\Pages\ListSections;
use App\Filament\Resources\SectionResource\RelationManagers\EnrollmentsRelationManager;
use App\Filament\Resources\SectionResource\RelationManagers\LecturesRelationManager;
use App\Models\Course;
use App\Models\Pricing;
use App\Models\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Academics';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Section Metadata')
                    ->schema([
                        Select::make('course_id')
                            ->label('Course')
                            ->searchable()
                            ->relationship('course', 'name')
                            ->options(Course::all()->pluck('name', 'id'))
                            ->required(),
                        Select::make('pricing_id')
                            ->label('Pricing')
                            ->searchable()
                            ->relationship('pricing', 'name')
                            ->options(Pricing::all()->pluck('name', 'id'))
                            ->required(),
                        TextInput::make('code')
                            ->label('Section Code')
                            ->maxLength(3)
                            ->required(),
                        TextInput::make('azure_team_id')
                            ->label('Azure Team ID')
                            ->unique()
                            ->required(),
                    ]),
                Fieldset::make('Lecture Delivery')
                    ->schema([
                        DateTimePicker::make('start_day')
                            ->label('Term Start')
                            ->displayFormat('l, d F Y')
                            ->withoutTime()
                            ->required(),
                        DateTimePicker::make('end_day')
                            ->label('Term End')
                            ->displayFormat('l, d F Y')
                            ->withoutTime()
                            ->required(),
                        Select::make('delivery_method')
                            ->label('Delivery Method')
                            ->options(['Online', 'In Person', 'Hybrid'])
                            ->required(),
                        TextInput::make('seats')
                            ->label('Seats')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.name')->label('Course')->sortable(),
                TextColumn::make('course.tutor.name')->label('Tutor')->sortable(),
                TextColumn::make('code')->label('Section Code')->sortable(),
                TextColumn::make('delivery_method')->label('Delivery Method')->sortable(),
                TextColumn::make('seats')->label('Seats')->sortable(),
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

    public static function getGloballySearchableAttributes(): array
    {
        return ['code', 'azure_team_id', 'course.tutor.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Tutor' => $record->course->tutor->name,
        ];
    }

    public static function getRelations(): array
    {
        return [
            LecturesRelationManager::class,
            EnrollmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSections::route('/'),
            'create' => CreateSection::route('/create'),
            'edit' => EditSection::route('/{record}/edit'),
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
