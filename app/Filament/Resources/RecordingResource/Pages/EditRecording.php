<?php

namespace App\Filament\Resources\RecordingResource\Pages;

use App\Filament\Resources\RecordingResource;
use App\Jobs\SyncRecordingMetadata;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\ForceDeleteAction;
use Filament\Pages\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditRecording extends EditRecord
{
    protected static string $resource = RecordingResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('sync')->label('Sync Metadata')->action(fn () => SyncRecordingMetadata::dispatch($this->record)),
            DeleteAction::make(),
            ForceDeleteAction::make()->label('Force Delete'),
            RestoreAction::make()->label('Restore'),
        ];
    }
}
