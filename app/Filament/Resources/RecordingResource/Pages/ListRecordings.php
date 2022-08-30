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

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-video-camera';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Recordings Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Once they sync from Microsoft Teams, lecture recordings will show up here!';
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()->label('New Recording'),
        ];
    }
}
