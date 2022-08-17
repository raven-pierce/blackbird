<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\User;
use App\Notifications\InvoiceGenerated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;
use Spatie\Browsershot\Browsershot;

class SendInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private PaymentMyfatoorahApiV2 $gateway;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected User $user, protected array $invoiceItems)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->gateway = new PaymentMyfatoorahApiV2(
            config('payment.api_key'),
            config('payment.country_iso'),
            config('payment.test_mode')
        );

        $gatewayInvoice = $this->gateway->getInvoiceURL($this->prepareMetadata());

        $invoice = Invoice::create([
            'external_id' => $gatewayInvoice['invoiceId'],
            'user_id' => $this->user->id,
            'invoice_url' => $gatewayInvoice['invoiceURL'],
            'amount' => $this->prepareTotal(),
            'status' => 'Unpaid',
        ]);

        Browsershot::url(route('invoices.show', $invoice))
            ->format('A4')
            ->showBackground()
            ->savePdf(storage_path("app/resources/invoices/{$invoice->external_id}.pdf"));

        $this->user->notify(new InvoiceGenerated($invoice));
    }

    protected function prepareMetadata(): array
    {
        return [
            'Language' => 'en',
            'DisplayCurrencyIso' => config('payment.display_currency'),

            'NotificationOption' => 'ALL',
            'CallBackUrl' => route('payment.callback'),
            'ErrorUrl' => route('payment.callback'),
            'SourceInfo' => config('app.name'),

            'InvoiceValue' => $this->prepareTotal(),
            'InvoiceItems' => $this->invoiceItems,

            'CustomerName' => $this->user->name,
            'CustomerEmail' => $this->user->email,
        ];
    }

    protected function prepareTotal(): float|int
    {
        $subtotals = [];

        foreach ($this->invoiceItems as $item) {
            $subtotals[] = $item['UnitPrice'] * $item['Quantity'];
        }

        return array_sum($subtotals);
    }
}
