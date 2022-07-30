<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NumberFormatter;

class ReceiptController extends Controller
{
    public function index()
    {
        return view('billing.receipts', [
            'receipts' => auth()->user()->receipts()->paginate(10),
            'currencyFormatter' => NumberFormatter::create('en-US', NumberFormatter::CURRENCY),
        ]);
    }
}
