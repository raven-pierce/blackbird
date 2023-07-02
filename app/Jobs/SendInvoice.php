<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\User;
use App\Notifications\InvoiceGenerated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class SendInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected PaymentMyfatoorahApiV2 $gateway;

    protected Client $twilio;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected User $user, protected array $invoiceItems)
    {
        $this->gateway = new PaymentMyfatoorahApiV2(
            config('payment.api_key'),
            config('payment.country_iso'),
            config('payment.test_mode')
        );

        try {
            $this->twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
        } catch (ConfigurationException $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $gatewayInvoice = $this->gateway->getInvoiceURL($this->prepareMetadata());

        $invoice = Invoice::create([
            'external_id' => $gatewayInvoice['invoiceId'],
            'user_id' => $this->user->id,
            'invoice_url' => $gatewayInvoice['invoiceURL'],
            'amount' => $this->prepareTotal(),
            'status' => 'Unpaid',
        ]);

        $this->processItems($invoice);

        $this->user->notify(new InvoiceGenerated($invoice));
    }

    /**
     * Prepare the required metadata object
     * for the payment gateway.
     *
     *
     * @throws TwilioException
     */
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

            'MobileCountryCode' => $this->twilio->lookups->v2->phoneNumbers($this->user->guardian_phone)->fetch()->callingCountryCode,
            'CustomerMobile' => preg_replace('/[^0-9]/', '', $this->twilio->lookups->v2->phoneNumbers($this->user->guardian_phone)->fetch()->nationalFormat),
        ];
    }

    /**
     * Associate each of the enrollment invoice items
     * with the newly created invoice model.
     */
    protected function processItems(Invoice $invoice): void
    {
        foreach ($this->invoiceItems as $item) {
            $enrollment = $item['Passthrough'];

            $attendances = Attendance::query()->whereBelongsTo($enrollment)
                ->whereInvoiceId(null)
                ->wherePaid(false)->take($item['Quantity'])->get();

            $attendances->each(function (Attendance $attendance) use ($invoice) {
                $attendance->invoice_id = $invoice->id;
                $attendance->save();
            });
        }
    }

    /**
     * Calculate the invoice total.
     */
    protected function prepareTotal(): float|int
    {
        $subtotals = [];

        foreach ($this->invoiceItems as $item) {
            $subtotals[] = $item['UnitPrice'] * $item['Quantity'];
        }

        return array_sum($subtotals);
    }
}
