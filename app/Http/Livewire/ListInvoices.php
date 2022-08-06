<?php

namespace App\Http\Livewire;

use App\Models\Invoice;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ListInvoices extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery(): Builder
    {
        return Invoice::query()->whereBelongsTo(auth()->user());
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('external_id')->label('Reference')->sortable(),
            TextColumn::make('created_at')->label('Date')->dateTime('l, d F Y')->sortable(),
            TextColumn::make('amount')->label('Total')->sortable(),
            BadgeColumn::make('status')->label('Status')->sortable()->enum([
                'Paid' => 'Paid',
                'Unpaid' => 'Unpaid',
                'Void' => 'Void',
            ])->colors([
                'primary',
                'success' => 'Paid',
                'warning' => 'Unpaid',
                'danger' => 'Void',
            ])->icons([
                'heroicon-s-badge-check' => 'Paid',
                'heroicon-s-x-circle' => 'Unpaid',
                'heroicon-s-ban' => 'Void',
            ]),
        ];
    }

    protected function getTableFilters(): array
    {
        return [];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('pay')
                ->label('Pay')
                ->icon('heroicon-s-cash')
                ->url(fn (Invoice $record) => $record->invoice_url)
                ->hidden(fn (Invoice $record) => $record->status === 'Paid' | $record->status === 'Void'),
            Action::make('view')
                ->label('View')
                ->icon('heroicon-s-external-link')
                ->url(fn (Invoice $record) => route('invoices.show', $record)),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

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

    protected function getTableRecordUrlUsing(): \Closure
    {
        return fn (Model $record): string => route('invoices.show', $record);
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
        return 'Come back later when you\'ve attended some lectures!';
    }

    public function render(): View
    {
        return view('livewire.list-invoices');
    }
}
