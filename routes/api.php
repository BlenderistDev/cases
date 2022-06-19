<?php

use App\Http\Controllers\CategoriesController;
use App\Services\SteamInfo\SteamUserInfoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::get('/me', function(\App\Services\Loyalty\Loyalty $loyalty) {
//    var_dump((new \App\Services\Options\OptionsService())->get('azaza'));
//    var_dump((new \App\Services\Options\OptionsService())->get('azaza1'));
//    exit();

    $loyalty->afterPurchaseBonus(new \App\Services\Payments\Entities\PaymentInfoEntity("azaza", 1000));
//    var_dump($loyalty->updatePriceWithLoyalty(100));

//    var_dump(var_dump(Auth::user()));
    exit();
    return $service->getUserInfo('76561198015482493');
});
