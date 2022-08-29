<?php

namespace App\Filament\Resources\LectureResource\Pages;

use App\Filament\Resources\LectureResource;
use App\Models\Enrollment;
use App\Models\Lecture;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class TakeAttendance extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = LectureResource::class;

    protected static string $view = 'filament.resources.lecture-resource.pages.take-attendance';

    public Lecture $lecture;

    public function mount($record): void
    {
        $this->lecture = Lecture::find($record);
    }

    protected function getTableQuery(): Builder
    {
        return Enrollment::query()->whereBelongsTo($this->lecture->section);
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
            Action::make('present')
                ->label('Mark Present')
                ->icon('heroicon-s-check')
                ->visible(fn (Enrollment $record) => $record->attendances()->whereBelongsTo($this->lecture)->doesntExist())
                ->action(function (Enrollment $record) {
                    $record->attendances()->create([
                        'lecture_id' => $this->lecture->id,
                        'join_time' => $this->lecture->start_time,
                        'leave_time' => $this->lecture->end_time,
                        'duration' => $this->lecture->duration,
                    ]);

                    Notification::make()
                        ->title('Marked Present')
                        ->success()
                        ->send();
                }),
            Action::make('absent')
                ->label('Mark Absent')
                ->icon('heroicon-s-x')
                ->visible(fn (Enrollment $record) => $record->attendances()->whereBelongsTo($this->lecture)->exists())
                ->action(function (Enrollment $record) {
                    $record->attendances()->whereBelongsTo($this->lecture)->first()->forceDelete();

                    Notification::make()
                        ->title('Marked Absent')
                        ->success()
                        ->send();
                }),
            Action::make('paid')
                ->label('Mark Paid')
                ->icon('heroicon-s-cash')
                ->visible(fn (Enrollment $record) => $record->attendances()->whereBelongsTo($this->lecture)->wherePaid(false)->exists())
                ->action(function (Enrollment $record) {
                    $attendance = $record->attendances()->whereBelongsTo($this->lecture)->wherePaid(false)->first();
                    $attendance->paid = true;
                    $attendance->save();

                    Notification::make()
                        ->title('Marked Paid')
                        ->success()
                        ->send();
                }),
            Action::make('unpaid')
                ->label('Mark Unpaid')
                ->icon('heroicon-s-ban')
                ->visible(fn (Enrollment $record) => $record->attendances()->whereBelongsTo($this->lecture)->wherePaid(true)->exists())
                ->action(function (Enrollment $record) {
                    $attendance = $record->attendances()->whereBelongsTo($this->lecture)->wherePaid(true)->first();
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
