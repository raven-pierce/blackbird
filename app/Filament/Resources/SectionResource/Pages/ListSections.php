<?php

namespace App\Filament\Resources\SectionResource\Pages;

use App\Filament\Resources\SectionResource;
use App\Models\Section;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSections extends ListRecords
{
    protected static string $resource = SectionResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Section::search($searchQuery)->keys());
        }

        return $query;
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-user-group';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Sections Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Sections allow students to choose the timing that fits them most.';
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()->label('New Section'),
        ];
    }
}
