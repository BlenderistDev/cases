<?php

namespace App\Console;

use App\Services\Cases\Services\OpenDummyCaseService;
use App\Services\Options\OptionsService;
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
            ->everyFiveMinutes()
            ->name('update prices');

        $schedule->call(function (SkinUpdateService $skinUpdateService) {
            $skinUpdateService->updateSkins();
        })
            ->everyFiveMinutes()
            ->name('update skins');

        $schedule->call(function (OpenDummyCaseService $openDummyCaseService, OptionsService $optionsService) {
            $chance = (int) $optionsService->get('fake_case_open_chance');
            $rand = rand(0, $chance);
            if ($rand === $chance) {
                $openDummyCaseService->openCase();
            }
        })
            ->everyMinute()
            ->name('fake case open');
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
