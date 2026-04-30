<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WeeklyDigestNotification;
use App\Services\ReminderService;
use Illuminate\Console\Command;

class SendWeeklyDigestCommand extends Command
{
    protected $signature = 'app:send-weekly-digest';
    protected $description = 'Envoie un resume hebdomadaire aux utilisateurs qui l\'ont active';

    public function handle(): int
    {
        $sent = 0;

        $users = User::withoutGlobalScopes()
            ->whereNotNull('organization_id')
            ->whereNotNull('email_verified_at')
            ->get();

        foreach ($users as $user) {
            $digestPref = $user->getMeta('notifications.digest', 'weekly');

            if ($digestPref === 'never') continue;

            // Monthly: only send on 1st
            if ($digestPref === 'monthly' && now()->day !== 1) continue;

            $digest = ReminderService::getUserDigestData($user);

            // Skip if nothing to report
            if ($digest['overdue_count'] === 0 && $digest['upcoming_count'] === 0 && $digest['completed_count'] === 0) {
                continue;
            }

            $user->notify(new WeeklyDigestNotification($digest));
            $sent++;
        }

        $this->info("Resumes envoyes : {$sent}");
        return self::SUCCESS;
    }
}
