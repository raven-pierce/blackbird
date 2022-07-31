<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteAzureController;

/*
|--------------------------------------------------------------------------
| Socialite Routes
|--------------------------------------------------------------------------
|
| Here is where you can register OAuth routes for your application using
| Laravel Socialite.
|
*/

Route::controller(SocialiteAzureController::class)->group(function () {
    Route::get('/auth/azure', 'redirectToProvider')->name('socialite.azure');
    Route::get('/auth/azure/callback', 'handleProviderCallback');
});
