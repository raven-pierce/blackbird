<?php

namespace App\Filament\Resources\LectureResource\Pages;

use App\Filament\Resources\LectureResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLectures extends ListRecords
{
    protected static string $resource = LectureResource::class;

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
