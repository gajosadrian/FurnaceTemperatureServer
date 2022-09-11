<?php

namespace App\Console;

use App\Jobs;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(Jobs\Furnace\NotifyTemperatureHigh::class)->everyMinute();
        $schedule->job(Jobs\Furnace\NotifyTemperatureLow::class)->everyMinute();
    }
}
