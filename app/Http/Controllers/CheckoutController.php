<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('checkout', [
            'enrollment' => Enrollment::findOrFail($request->enrollment),
        ]);
    }
}
