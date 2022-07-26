<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
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

Route::get('/billing', [BillingController::class, 'index'])->middleware(['auth', 'verified'])->name('billing.index');

Route::post('/checkout', [CheckoutController::class, '__invoke'])->middleware(['auth', 'verified'])->name('checkout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
