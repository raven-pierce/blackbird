<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    public function index()
    {
        return view('billing.index', [
            'enrollments' => auth()->user()->enrollments()->with('unpaidAttendances')->has('unpaidAttendances')->get(),
        ]);
    }
}
