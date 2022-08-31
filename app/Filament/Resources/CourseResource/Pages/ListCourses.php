<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-academic-cap';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Courses Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Create some courses so students can enroll!';
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()->label('New Course'),
        ];
    }
}
