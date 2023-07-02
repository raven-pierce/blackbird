<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Jobs\SyncLectureAttendance;
use App\Models\Attendance;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Attendance::search($searchQuery)->keys());
        }

        return $query;
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-bookmark';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Attendances Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'This is where you\'ll be able to see who attended what lecture.';
    }

    protected function getActions(): array
    {
        return [
            Action::make('syncAttendance')
                ->label('Sync Attendance')
                ->action(fn () => SyncLectureAttendance::dispatch())
                ->visible(fn () => auth()->user()->hasRole('icarus')),
            CreateAction::make()->label('New Attendance'),
        ];
    }
}
