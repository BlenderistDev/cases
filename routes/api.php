<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\OpenCaseController;
use App\Http\Controllers\PaymentGiftWinnerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSkinController;
use App\Services\SteamTrades\classes\SteamTrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/cases', function () {
    var_dump("azaza");
});


Route::get('/categories', [CategoriesController::class, 'index']);

Route::get('/paymentgift/winner', [PaymentGiftWinnerController::class, 'index']);

Route::post('/user/skin/sell', [UserSkinController::class, 'sell']);

Route::post('/user/skin/out', [UserSkinController::class, 'out']);

Route::get('/loyalty', [LoyaltyController::class, 'index']);

Route::post('/case/open', [OpenCaseController::class, 'index']);

Route::post('/user/tradelink', [UserController::class, 'setTradeLink']);

Route::get('/me', [UserController::class, 'index']);
