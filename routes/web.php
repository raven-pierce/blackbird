<?php

use App\Http\Controllers\CoursesController;
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
    return view('landing');
})->name('landing');

Route::controller(CoursesController::class)->group(function () {
    Route::get('/courses', 'index')->name('courses');
});

Route::get('/tutors', function () {
    return view('tutors');
})->name('tutors');

Route::get('/materials', function () {
    return view('materials');
})->name('materials');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
