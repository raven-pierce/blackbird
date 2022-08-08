<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;
use NumberFormatter;

class InvoiceController extends Controller
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

    public function index()
    {
        return view('invoices.index');
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', [
            'invoice' => $invoice,
            'gatewayInvoice' => $this->gateway->getPaymentStatus($invoice->external_id, 'invoiceid'),
            'currencyFormatter' => NumberFormatter::create('en-US', NumberFormatter::CURRENCY),
        ]);
    }

    public function download(Invoice $invoice)
    {
        //
    }
}
