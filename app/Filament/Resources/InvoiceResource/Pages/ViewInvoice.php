<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\ViewRecord;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;
use NumberFormatter;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected static string $view = 'filament.resources.invoices.pages.view-invoice';

    public function getViewData(): array
    {
        $gateway = new PaymentMyfatoorahApiV2(
            config('payment.api_key'),
            config('payment.country_iso'),
            config('payment.test_mode')
        );

        return [
            'gatewayInvoice' => $gateway->getPaymentStatus($this->record->external_id, 'invoiceid'),
            'currencyFormatter' => NumberFormatter::create('en-US', NumberFormatter::CURRENCY),
        ];
    }
}
