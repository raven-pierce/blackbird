<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Jobs\SyncDirectoryUsers;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-users';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Users Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'On this page, you\'l find all currently registered users.';
    }

    protected function getActions(): array
    {
        return [
            Action::make()->label('Sync Directory')->action(fn () => SyncDirectoryUsers::dispatch()),
            CreateAction::make()->label('New User'),
        ];
    }
}
