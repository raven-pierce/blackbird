<?php

namespace App\Filament\Resources\LectureResource\RelationManagers;

use App\Imports\Attendances;
use App\Models\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
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
use Maatwebsite\Excel\Facades\Excel;

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
                DateTimePicker::make('join_time')
                    ->label('Join Time')
                    ->displayFormat('d F Y, h:i A')
                    ->withoutSeconds()
                    ->required(),
                DateTimePicker::make('leave_time')
                    ->label('Leave Time')
                    ->displayFormat('d F Y, h:i A')
                    ->withoutSeconds()
                    ->required(),
                TextInput::make('duration')
                    ->label('Duration in Minutes')
                    ->numeric()
                    ->required(),
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
                TextColumn::make('join_time')->label('Join Time')->dateTime('l, d F Y h:i A')->sortable(),
                TextColumn::make('leave_time')->label('Leave Time')->dateTime('l, d F Y h:i A')->sortable(),
                TextColumn::make('duration')->label('Duration')->sortable(),
                BooleanColumn::make('paid')->label('Paid?')->sortable()->trueIcon('heroicon-s-badge-check')->falseIcon('heroicon-s-x-circle'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()->label('New Attendance'),
                Action::make('Import')
                    ->action(function (array $data) {
                        Excel::import(new Attendances(), $data['attachment']);

                        Notification::make()
                            ->title('Attendances Imported')
                            ->success()
                            ->send();
                    })
                    ->form([
                        FileUpload::make('attachment')->required(),
                    ])
                    ->visible(fn () => auth()->user()->hasRole('icarus')),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make()->label('Force Delete'),
                RestoreAction::make()->label('Restore'),
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
