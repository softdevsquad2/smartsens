<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Mark absent students every day at 4 PM
        $schedule->command('attendance:mark-absent')
            ->dailyAt('16:00');

        // Check and mark students who did not checkout after 17:00
        $schedule->command('attendance:check-bolos')
            ->dailyAt('09:56');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
