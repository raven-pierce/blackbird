<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Models\Pricing;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sections';

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Section Metadata')
                    ->schema([
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
                TextColumn::make('start_day')->label('Term Start')->dateTime('l, d F Y')->sortable(),
                TextColumn::make('end_day')->label('Term End')->dateTime('l, d F Y')->sortable(),
                TextColumn::make('code')->label('Section Code')->sortable(),
                TextColumn::make('delivery_method')->label('Delivery Method')->sortable(),
                TextColumn::make('seats')->label('Seats')->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
