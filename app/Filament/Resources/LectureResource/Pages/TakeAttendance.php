<?php

namespace App\Filament\Resources\LectureResource\Pages;

use App\Filament\Resources\LectureResource;
use App\Models\Enrollment;
use App\Models\Lecture;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class TakeAttendance extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = LectureResource::class;

    protected static string $view = 'filament.resources.lectures.pages.take-attendance';

    public Lecture $record;

    public function mount(): void
    {
        abort_unless(LectureResource::canEdit($this->record), 403);
    }

    protected function getTableQuery(): Builder
    {
        return Enrollment::query()->whereBelongsTo($this->record->section);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('student.name')->label('Student')->sortable(),
            TextColumn::make('student.email')->label('Azure Email')->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            //
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('markPresent')
                ->label('Mark Present')
                ->icon('heroicon-s-check')
                ->visible(fn (Enrollment $record) => $record->attendances()->whereBelongsTo($this->record)->doesntExist())
                ->action(function (Enrollment $record) {
                    $record->attendances()->create([
                        'lecture_id' => $this->record->id,
                        'join_date' => $this->record->start_date,
                        'leave_date' => $this->record->end_date,
                        'duration' => $this->record->duration,
                    ]);

                    Notification::make()
                        ->title('Marked Present')
                        ->success()
                        ->send();
                }),
            Action::make('markAbsent')
                ->label('Mark Absent')
                ->icon('heroicon-s-x')
                ->visible(fn (Enrollment $record) => $record->attendances()->whereBelongsTo($this->record)->exists())
                ->action(function (Enrollment $record) {
                    $record->attendances()->whereBelongsTo($this->record)->first()->forceDelete();

                    Notification::make()
                        ->title('Marked Absent')
                        ->success()
                        ->send();
                }),
            Action::make('markPaid')
                ->label('Mark Paid')
                ->icon('heroicon-s-cash')
                ->visible(fn (Enrollment $record) => $record->attendances()->whereBelongsTo($this->record)->wherePaid(false)->exists())
                ->action(function (Enrollment $record) {
                    $attendance = $record->attendances()->whereBelongsTo($this->record)->wherePaid(false)->first();
                    $attendance->paid = true;
                    $attendance->save();

                    Notification::make()
                        ->title('Marked Paid')
                        ->success()
                        ->send();
                }),
            Action::make('markUnpaid')
                ->label('Mark Unpaid')
                ->icon('heroicon-s-ban')
                ->visible(fn (Enrollment $record) => $record->attendances()->whereBelongsTo($this->record)->wherePaid(true)->exists())
                ->action(function (Enrollment $record) {
                    $attendance = $record->attendances()->whereBelongsTo($this->record)->wherePaid(true)->first();
                    $attendance->paid = false;
                    $attendance->save();

                    Notification::make()
                        ->title('Marked Unpaid')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            //
        ];
    }
}
