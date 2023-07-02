<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Models\Invoice;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Invoice::search($searchQuery)->keys());
        }

        return $query;
    }

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
