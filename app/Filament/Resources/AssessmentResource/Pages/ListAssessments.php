<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use App\Models\Assessment;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAssessments extends ListRecords
{
    protected static string $resource = AssessmentResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Assessment::search($searchQuery)->keys());
        }

        return $query;
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-chart-bar';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Assessments Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Here you can define assessments that have taken place externally.';
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()->label('New Assessment'),
        ];
    }
}
