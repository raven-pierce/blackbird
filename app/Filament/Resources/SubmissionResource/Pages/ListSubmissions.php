<?php

namespace App\Filament\Resources\SubmissionResource\Pages;

use App\Filament\Resources\SubmissionResource;
use App\Models\Submission;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSubmissions extends ListRecords
{
    protected static string $resource = SubmissionResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Submission::search($searchQuery)->keys());
        }

        return $query;
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-paper-clip';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Submissions Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Here you will find all assessment submissions made by students.';
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()->label('New Submission'),
        ];
    }
}
