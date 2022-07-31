<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ReceiptController;
use App\Services\MicrosoftGraph;
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

Route::get('/', function () {
    return view('home');
})->name('home');

Route::controller(CourseController::class)->group(function () {
    Route::get('/courses', 'index')->name('courses.index');
    Route::get('/courses/{course}', 'show')->name('courses.show');
});

Route::resource('enrollments', EnrollmentController::class)->middleware(['auth', 'verified']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/receipts', [ReceiptController::class, 'index'])->name('receipts');
    Route::post('/billing/checkout', [CheckoutController::class, '__invoke'])->name('checkout');
});

Route::get('/graph', function (MicrosoftGraph $microsoftGraph) {
    dd($microsoftGraph->getAllGroups());
});

require __DIR__.'/socialite.php';
