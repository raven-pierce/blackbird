<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

Route::controller(CourseController::class)->group(function () {
    Route::get('/courses', 'index')->name('courses.index');
    Route::get('/courses/{course}', 'show')->name('courses.show');
});

Route::resource('enrollments', EnrollmentController::class)->middleware('auth');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
