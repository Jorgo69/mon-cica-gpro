<?php

namespace App\Services;

use App\Enums\AccountType;
use App\Enums\ActivityStatus;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ReminderService
{
    private static array $terminalStatuses = [
        ActivityStatus::COMPLETED,
        ActivityStatus::ABANDONED,
        ActivityStatus::STOPPED,
    ];

    public static function activitiesWithDeadlineIn(int $days): Builder
    {
        return Activity::withoutGlobalScopes()
            ->whereDate('end_date', Carbon::today()->addDays($days))
            ->whereNotIn('status', array_map(fn($s) => $s->value, self::$terminalStatuses))
            ->whereNotNull('responsible_user_id')
            ->with(['responsibleUser', 'result.specificObjective.logicalFramework.project']);
    }

    public static function overdueActivities(): Builder
    {
        return Activity::withoutGlobalScopes()
            ->whereDate('end_date', '<', Carbon::today())
            ->whereNotIn('status', array_map(fn($s) => $s->value, self::$terminalStatuses))
            ->whereNotNull('responsible_user_id')
            ->with(['responsibleUser', 'result.specificObjective.logicalFramework.project']);
    }

    public static function shouldSendReminder(User $user, string $activityId, string $reminderType): bool
    {
        $key = "reminders.last_sent.{$reminderType}.{$activityId}";
        $lastSent = $user->getMeta($key);

        if ($lastSent === Carbon::today()->toDateString()) {
            return false;
        }

        $user->setMeta($key, Carbon::today()->toDateString());
        return true;
    }

    public static function getOrgAdmins(string $orgId): \Illuminate\Support\Collection
    {
        return User::withoutGlobalScopes()
            ->where('organization_id', $orgId)
            ->where('role', AccountType::ORG_ADMIN->value)
            ->get();
    }

    public static function getUserDigestData(User $user): array
    {
        $orgId = $user->organization_id;

        $overdueCount = Activity::withoutGlobalScopes()
            ->whereHas('result.specificObjective.logicalFramework.project', fn($q) => $q->where('organization_id', $orgId))
            ->whereDate('end_date', '<', Carbon::today())
            ->whereNotIn('status', array_map(fn($s) => $s->value, self::$terminalStatuses))
            ->when($user->role !== AccountType::ORG_ADMIN, fn($q) => $q->where('responsible_user_id', $user->id))
            ->count();

        $upcomingCount = Activity::withoutGlobalScopes()
            ->whereHas('result.specificObjective.logicalFramework.project', fn($q) => $q->where('organization_id', $orgId))
            ->whereBetween('end_date', [Carbon::today(), Carbon::today()->addWeek()])
            ->whereNotIn('status', array_map(fn($s) => $s->value, self::$terminalStatuses))
            ->when($user->role !== AccountType::ORG_ADMIN, fn($q) => $q->where('responsible_user_id', $user->id))
            ->count();

        $completedCount = Activity::withoutGlobalScopes()
            ->whereHas('result.specificObjective.logicalFramework.project', fn($q) => $q->where('organization_id', $orgId))
            ->where('status', ActivityStatus::COMPLETED->value)
            ->where('updated_at', '>=', Carbon::today()->subWeek())
            ->when($user->role !== AccountType::ORG_ADMIN, fn($q) => $q->where('responsible_user_id', $user->id))
            ->count();

        $projectsCount = \App\Models\Project::withoutGlobalScopes()
            ->where('organization_id', $orgId)
            ->where('status', \App\Enums\ProjectStatus::ACTIVE->value)
            ->count();

        return [
            'overdue_count' => $overdueCount,
            'upcoming_count' => $upcomingCount,
            'completed_count' => $completedCount,
            'projects_count' => $projectsCount,
        ];
    }
}
