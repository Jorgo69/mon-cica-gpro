<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Rappels deadlines : quotidien a 08h UTC
        $schedule->command('app:send-deadline-reminders')->dailyAt('08:00');

        // Alertes retard + escalade : quotidien a 09h UTC
        $schedule->command('app:send-overdue-alerts')->dailyAt('09:00');

        // Resume hebdomadaire : lundi a 08h UTC
        $schedule->command('app:send-weekly-digest')->weeklyOn(1, '08:00');

        // RGPD : purge organisations expirees (30j apres suppression planifiee)
        $schedule->command('gpro:purge-expired-orgs')->dailyAt('03:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
