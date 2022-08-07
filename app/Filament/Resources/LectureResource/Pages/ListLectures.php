<?php

namespace App\Filament\Resources\LectureResource\Pages;

use App\Filament\Resources\LectureResource;
use App\Models\Lecture;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListLectures extends ListRecords
{
    protected static string $resource = LectureResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Lecture::search($searchQuery)->keys());
        }

        return $query;
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-clock';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Lectures Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Here you can create, delete, and generate lectures for your sections.';
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()->label('New Lecture'),
        ];
    }
}
