<?php

use App\Http\Controllers\SteamAuthController;
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

Route::get('auth/steam', [SteamAuthController::class, 'redirectToSteam'])->name('auth.steam');
Route::get('auth/steam/handle', [SteamAuthController::class, 'handle'])->name('auth.steam.handle');

Route::get('/payment/skin', [\App\Http\Controllers\SkinsBackController::class, 'index']);
Route::get('/payment/skin/callback', [\App\Http\Controllers\SkinsBackController::class, 'callback']);

Route::get('/me', function () {
    return auth()->user();
});

Route::get('logout', [SteamAuthController::class, 'logout'])->name('logout');
