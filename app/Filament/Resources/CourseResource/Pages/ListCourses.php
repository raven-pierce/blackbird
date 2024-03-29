<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Models\Course;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Course::search($searchQuery)->keys());
        }

        return $query;
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
