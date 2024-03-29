<?php

namespace App\Filament\Resources\SectionResource\RelationManagers;

use Filament\Forms\Components\DateTimePicker;
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

class LecturesRelationManager extends RelationManager
{
    protected static string $relationship = 'lectures';

    protected static ?string $recordTitleAttribute = 'start_date';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DateTimePicker::make('start_date')
                    ->label('Lecture Start')
                    ->displayFormat('l, d F Y h:i A')
                    ->withoutSeconds()
                    ->required(),
                DateTimePicker::make('end_date')
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
                TextColumn::make('start_date')->label('Lecture Start')->dateTime('l, d F Y h:i A')->sortable(),
                TextColumn::make('end_date')->label('Lecture End')->dateTime('l, d F Y h:i A')->sortable(),
            ])
            ->defaultSort('start_date')
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()->label('New Lecture'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make()->label('Force Delete'),
                RestoreAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->label('Delete Selected'),
                RestoreBulkAction::make()->label('Restore Selected'),
                ForceDeleteBulkAction::make()->label('Force Delete Selected'),
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
