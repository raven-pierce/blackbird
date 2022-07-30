<?php

namespace App\Http\Controllers;

use NumberFormatter;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
