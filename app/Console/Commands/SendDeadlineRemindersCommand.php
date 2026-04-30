<?php

namespace App\Console\Commands;

use App\Notifications\DeadlineApproachingNotification;
use App\Services\ReminderService;
use Illuminate\Console\Command;

class SendDeadlineRemindersCommand extends Command
{
    protected $signature = 'app:send-deadline-reminders';
    protected $description = 'Envoie des rappels pour les activites dont la deadline approche (J-7, J-3, J-1)';

    public function handle(): int
    {
        $days = config('gpro.notifications.reminder_days', [7, 3, 1]);
        $sent = 0;

        foreach ($days as $d) {
            $activities = ReminderService::activitiesWithDeadlineIn($d)->get();

            foreach ($activities as $activity) {
                $user = $activity->responsibleUser;
                if (!$user) continue;

                if (!ReminderService::shouldSendReminder($user, $activity->id, "deadline_{$d}")) {
                    continue;
                }

                $user->notify(new DeadlineApproachingNotification($activity, $d));
                $sent++;
            }
        }

        $this->info("Rappels envoyes : {$sent}");
        return self::SUCCESS;
    }
}
