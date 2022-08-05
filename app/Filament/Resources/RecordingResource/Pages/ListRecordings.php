<?php

namespace App\Filament\Resources\RecordingResource\Pages;

use App\Filament\Resources\RecordingResource;
use App\Models\Recording;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListRecordings extends ListRecords
{
    protected static string $resource = RecordingResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Recording::search($searchQuery)->keys());
        }

        return $query;
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
