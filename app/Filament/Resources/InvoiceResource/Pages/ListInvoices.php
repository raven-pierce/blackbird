<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-cash';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Invoices Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'You\'ll find them here automatically when they meet the minimum threshold!';
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()->label('New Invoice'),
        ];
    }
}
