<?php

namespace App\Providers;

use App\Services\Loyalty\Discounts\NameLoyalty\NameLoyalty;
use App\Services\Loyalty\Discounts\PaymentBonus\PaymentBonus;
use App\Services\Loyalty\Loyalty;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Loyalty::class, function (Application $app) {
            return new Loyalty([
                $app->get(NameLoyalty::class),
                $app->get(PaymentBonus::class)
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
