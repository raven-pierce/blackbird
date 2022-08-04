<?php

namespace App\Filament\Resources\TagResource\Pages;

use App\Filament\Resources\TagResource;
use App\Models\Tag;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Tag::search($searchQuery)->keys());
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
