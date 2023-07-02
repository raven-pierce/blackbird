<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\ForceDeleteAction;
use Filament\Pages\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('markPaid')
                ->label('Mark Paid')
                ->visible(fn () => $this->record->status != 'Paid')
                ->action(function () {
                    $this->record->markPaid();

                    Notification::make()
                        ->title('Invoice Paid')
                        ->success()
                        ->send();
                }),
            Action::make('markUnpaid')
                ->label('Mark Unpaid')
                ->visible(fn () => $this->record->status === 'Paid')
                ->action(function () {
                    $this->record->markUnpaid();

                    Notification::make()
                        ->title('Invoice Unpaid')
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
            ForceDeleteAction::make()->label('Force Delete'),
            RestoreAction::make(),
        ];
    }
}
