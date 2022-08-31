<?php

namespace App\Filament\Resources\TagResource\Pages;

use App\Filament\Resources\TagResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-tag';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Tags Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Create some so you can assign them to courses!';
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()->label('New Tag'),
        ];
    }
}
