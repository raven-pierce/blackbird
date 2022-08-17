<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/payment/callback', [PaymentController::class, 'handleProviderCallback'])->name('payment.callback');
});

Route::get('/sync', function () {
   foreach (User::all() as $user) {
       $user->assignRole('user');
   }
});
