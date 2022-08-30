<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Models\Attendance;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

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
            CreateAction::make(),
        ];
    }
}
