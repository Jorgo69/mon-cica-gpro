<?php

namespace App\Console\Commands;

use App\Notifications\ActivityOverdueNotification;
use App\Services\ReminderService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendOverdueAlertsCommand extends Command
{
    protected $signature = 'app:send-overdue-alerts';
    protected $description = 'Envoie des alertes pour les activites en retard, avec escalade apres 7 jours';

    public function handle(): int
    {
        $escalationDays = config('gpro.notifications.escalation_after_days', 7);
        $activities = ReminderService::overdueActivities()->get();
        $sent = 0;

        foreach ($activities as $activity) {
            $user = $activity->responsibleUser;
            if (!$user) continue;

            $daysOverdue = (int) Carbon::today()->diffInDays($activity->end_date);

            // Notify responsible user
            if (ReminderService::shouldSendReminder($user, $activity->id, 'overdue')) {
                $user->notify(new ActivityOverdueNotification($activity, $daysOverdue));
                $sent++;
            }

            // Escalation: notify project creator + org admins
            if ($daysOverdue >= $escalationDays) {
                $project = $activity->project;
                if (!$project) continue;

                // Notify project creator
                if ($project->creator && $project->creator->id !== $user->id) {
                    if (ReminderService::shouldSendReminder($project->creator, $activity->id, 'escalation')) {
                        $project->creator->notify(new ActivityOverdueNotification($activity, $daysOverdue, true));
                        $sent++;
                    }
                }

                // Notify org admins
                if ($project->organization_id) {
                    $admins = ReminderService::getOrgAdmins($project->organization_id);
                    foreach ($admins as $admin) {
                        if ($admin->id === $user->id || $admin->id === $project->creator_user_id) continue;
                        if (ReminderService::shouldSendReminder($admin, $activity->id, 'escalation')) {
                            $admin->notify(new ActivityOverdueNotification($activity, $daysOverdue, true));
                            $sent++;
                        }
                    }
                }
            }
        }

        $this->info("Alertes retard envoyees : {$sent}");
        return self::SUCCESS;
    }
}
