<?php

namespace App\Http\Controllers;

use NumberFormatter;

class BillingController extends Controller
{
    public function index()
    {
        return view('billing.index', [
            'enrollments' => auth()->user()->enrollments()->with('unpaidAttendances')->has('unpaidAttendances')->get(),
            'spellOutFormatter' => NumberFormatter::create('en-US', NumberFormatter::SPELLOUT),
            'currencyFormatter' => NumberFormatter::create('en-US', NumberFormatter::CURRENCY),
        ]);
    }
}
