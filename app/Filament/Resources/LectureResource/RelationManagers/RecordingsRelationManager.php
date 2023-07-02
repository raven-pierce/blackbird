<?php

namespace App\Filament\Resources\LectureResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;

class RecordingsRelationManager extends RelationManager
{
    protected static string $relationship = 'recordings';

    protected static ?string $recordTitleAttribute = 'file_url';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                TextColumn::make('file_name')->label('File Name')->sortable(),
                TextColumn::make('azure_item_id')->label('Azure ID')->sortable(),
                TextColumn::make('created_at')->label('Uploaded')->since()->sortable(),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->label('Delete Selected'),
            ]);
    }
}
