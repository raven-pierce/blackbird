<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-identification';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Enrollments Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Here, you\'l be able to manage all student enrollments.';
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()->label('New Enrollment'),
        ];
    }
}
