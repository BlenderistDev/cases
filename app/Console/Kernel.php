<?php

namespace App\Console;

use App\Services\Cases\Services\OpenDummyCaseService;
use App\Services\Loyalty\Discounts\PaymentGift\PaymentGift;
use App\Services\Loyalty\Discounts\PaymentGift\Repositories\PaymentGiftRepository;
use App\Services\Options\OptionsService;
use App\Services\SkinsBack\Services\MissingCallbackService;
use App\Services\SkinUpdate\SkinUpdateService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function (SkinUpdateService $skinUpdateService) {
            $skinUpdateService->updatePrices();
        })
            ->everySixHours()
            ->name('update prices');

        $schedule->call(function (SkinUpdateService $skinUpdateService) {
            $skinUpdateService->updateSkins();
        })
            ->everySixHours()
            ->name('update skins');

        $schedule->call(function (OpenDummyCaseService $openDummyCaseService, OptionsService $optionsService) {
            $chance = (int) $optionsService->get('fake_case_open_chance');

            $start = microtime(true);
            $i = 0;

            while (microtime(true) - $start < 59) {
                if ((microtime(true) - $start) > $i) {
                    $rand = rand(0, $chance);
                    if ($rand === $chance) {
                        $openDummyCaseService->openCase();
                    }
                    $i++;
                }
            }

        })
            ->cron('* * * * *')
            ->name('fake case open');

        /** @var PaymentGiftRepository $paymentGiftRepository */
        $paymentGiftRepository = $this->app->get(PaymentGiftRepository::class);
        $paymentGiftHours = $paymentGiftRepository
            ->getPaymentGiftInfo()
            ->getHours();

        if ($paymentGiftHours) {
            $schedule->call(function (PaymentGift $paymentGiftService) {
                $paymentGiftList = \App\Models\PaymentGift::all();
                foreach ($paymentGiftList as $paymentGift) {
                    $paymentGiftService->raffle($paymentGift->id);
                }
            })
                ->cron("0 */$paymentGiftHours * * *")
                ->name('payment gift raffle');
        }

        $schedule->call(function (MissingCallbackService $missingCallbackService) {
            $missingCallbackService->checkMissingCallbacks();
        })
            ->everyThreeMinutes()
            ->name('check skinsback missing callbacks');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
