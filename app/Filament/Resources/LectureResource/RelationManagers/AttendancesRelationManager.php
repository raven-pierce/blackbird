<?php

namespace App\Filament\Resources\LectureResource\RelationManagers;

use App\Models\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendancesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    protected static ?string $recordTitleAttribute = 'enrollment_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('enrollment_id')
                    ->label('Student')
                    ->searchable()
                    ->relationship('enrollment', 'id', function (Builder $query, $livewire) {
                        $section = Section::find($livewire->ownerRecord->section_id);

                        if (! $section) {
                            return $query;
                        }

                        return $query->whereBelongsTo($section);
                    })
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->student->name)
                    ->required(),
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
                    ->label('Attendance Paid?')
                    ->onIcon('heroicon-s-cash')
                    ->offIcon('heroicon-s-book-open')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('enrollment.student.name')->label('Student')->sortable(),
                TextColumn::make('join_date')->label('Join Time')->dateTime('l, d F Y h:i A')->sortable(),
                TextColumn::make('leave_date')->label('Leave Time')->dateTime('l, d F Y h:i A')->sortable(),
                TextColumn::make('duration')->label('Duration')->sortable(),
                BooleanColumn::make('paid')->label('Paid?')->sortable()->trueIcon('heroicon-s-badge-check')->falseIcon('heroicon-s-x-circle'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()->label('New Attendance'),
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
