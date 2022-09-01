<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Filament\Notifications\Notification;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;

class PaymentController extends Controller
{
    private PaymentMyfatoorahApiV2 $gateway;

    public function __construct()
    {
        $this->gateway = new PaymentMyfatoorahApiV2(
            config('payment.api_key'),
            config('payment.country_iso'),
            config('payment.test_mode')
        );
    }

    public function handleProviderCallback()
    {
        $gatewayInvoice = $this->gateway->getPaymentStatus(request('paymentId'), 'PaymentId');

        if ($gatewayInvoice->InvoiceStatus === 'Failed') {
            Notification::make()
                ->title('Invoice Payment Failed')
                ->body($this->resolveErrorCodes($gatewayInvoice))
                ->danger()
                ->send();

            return redirect()->route('invoices.index');
        }

        if ($gatewayInvoice->InvoiceStatus === 'Expired') {
            $invoice = Invoice::query()->whereExternalId($gatewayInvoice->InvoiceId)->firstOrFail();

            $invoice->status = 'Expired';

            $invoice->save();

            Notification::make()
                ->title('Invoice Expired')
                ->body('Please regenerate the invoice to try again.')
                ->danger()
                ->send();

            return redirect()->route('invoices.index');
        }

        $invoice = Invoice::query()->whereExternalId($gatewayInvoice->InvoiceId)->firstOrFail();

        $invoice->status = 'Paid';

        $invoice->save();

        Notification::make()
            ->title('Invoice Paid')
            ->body('Thank you for paying your invoice!')
            ->success()
            ->send();

        return redirect()->route('invoices.index');
    }

    private function resolveErrorCodes(object $gatewayInvoice): string
    {
        if ($gatewayInvoice->focusTransaction->ErrorCode === 'MF001') {
            return '3D Secure Authentication Failed.';
        }

        if ($gatewayInvoice->focusTransaction->ErrorCode === 'MF002') {
            return 'The transaction has been declined by your bank.';
        }

        if ($gatewayInvoice->focusTransaction->ErrorCode === 'MF003') {
            return 'The transaction has been blocked by your bank.';
        }

        if ($gatewayInvoice->focusTransaction->ErrorCode === 'MF004') {
            return 'Your payment method has insufficient funds.';
        }

        if ($gatewayInvoice->focusTransaction->ErrorCode === 'MF005') {
            return 'Request Timeout. Please try again.';
        }

        if ($gatewayInvoice->focusTransaction->ErrorCode === 'MF006') {
            return 'You\'ve canceled the transaction.';
        }

        if ($gatewayInvoice->focusTransaction->ErrorCode === 'MF007') {
            return 'Your payment method has expired.';
        }

        if ($gatewayInvoice->focusTransaction->ErrorCode === 'MF008') {
            return 'Your payment method issuer is not responding. Please try again later.';
        }

        if ($gatewayInvoice->focusTransaction->ErrorCode === 'MF009') {
            return 'The transaction has been marked as fraud.';
        }

        if ($gatewayInvoice->focusTransaction->ErrorCode === 'MF010') {
            return 'Your payment method\'s security code is incorrect.';
        }

        return 'We\'re not sure what happened. Please try again later.';
    }
}
