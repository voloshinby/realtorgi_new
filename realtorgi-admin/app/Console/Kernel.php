<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\TestCommand::class,
        Commands\Auctions::class,
        Commands\NotificationEveryDay::class,
        Commands\NotificationEveryTenMinutes::class,
        Commands\AuctionsMinPrice::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('cron:test')->everyTwoMinutes();
        $schedule->command('auction:status')->everyMinute();
        $schedule->command('auction:notification_day')->hourly();
        $schedule->command('auction:pricemin')->hourly();
        $schedule->command('auction:notification_ten')->everyTenMinutes();
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
