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
        // Send daily notifications at 8:00 AM
        $schedule->command('notifications:send-daily')->dailyAt('08:00');
        
        // Send pledge reminders weekly on Monday at 9:00 AM
        $schedule->command('pledges:send-reminders')->weekly()->mondays()->at('09:00');
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
