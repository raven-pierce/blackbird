<?php

namespace App\Filament\Resources\PricingResource\Pages;

use App\Filament\Resources\PricingResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPricings extends ListRecords
{
    protected static string $resource = PricingResource::class;

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-credit-card';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Pricing Tiers Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Create some so you can control a section\'s pricing!';
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()->label('New Pricing Tier'),
        ];
    }
}
