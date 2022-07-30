<?php

namespace App\Http\Controllers;

use NumberFormatter;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('billing.checkout', [
            'enrollment' => Enrollment::findOrFail($request->enrollment),
            'spellOutFormatter' => NumberFormatter::create('en-US', NumberFormatter::SPELLOUT),
            'currencyFormatter' => NumberFormatter::create('en-US', NumberFormatter::CURRENCY),
        ]);
    }
}
