<?php

namespace App\Services\Queries;

use App\Models\Project;
use App\Models\Activity;
use App\Models\Budget;
use App\Models\ProjectUpdate;
use App\Enums\AccountType;
use App\Enums\ProjectStatus;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ProjectStatsQueryService
{
    public function getDashboardStats($user, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $orgId = session('current_organization_id');

        $projectQuery  = Project::query();
        $activityQuery = Activity::query();
        $budgetQuery   = Budget::query();
        $updateQuery   = ProjectUpdate::query();

        // Multi-tenancy : filter par organisation active (sauf system_admin)
        if ($user->account_type !== AccountType::SYSTEM_ADMIN) {
            if ($orgId) {
                $projectQuery->where('organization_id', $orgId);
                $activityQuery->whereHas('project', fn($q) => $q->where('organization_id', $orgId));
                $updateQuery->whereHas('project', fn($q) => $q->where('organization_id', $orgId));
                $budgetQuery->whereHas('project', fn($q) => $q->where('organization_id', $orgId));
            } else {
                // Aucune org en session → résultats vides
                $projectQuery->whereRaw('1 = 0');
                $activityQuery->whereRaw('1 = 0');
            }
        }

        if ($startDate && $endDate) {
            $projectQuery->whereBetween('created_at', [$startDate, $endDate]);
            $activityQuery->whereBetween('created_at', [$startDate, $endDate]);
            $updateQuery->whereBetween('date', [$startDate, $endDate]);
        }

        return $this->formatStats($projectQuery, $activityQuery, $budgetQuery, $updateQuery);
    }

    private function formatStats(Builder $projects, Builder $activities, Builder $budgets, Builder $updates): array
    {
        $totalProjects      = (clone $projects)->count();
        $projectsInProgress = (clone $projects)->where('status', ProjectStatus::ACTIVE)->count();
        $projectsCompleted  = (clone $projects)->where('status', ProjectStatus::COMPLETED)->count();
        $projectsDraft      = (clone $projects)->where('status', ProjectStatus::DRAFT)->count();
        $projectsCanceled   = (clone $projects)->where('status', ProjectStatus::CANCELLED)->count();

        $totalActivities      = (clone $activities)->count();
        $activitiesInProgress = (clone $activities)->where('status', \App\Enums\ActivityStatus::ONGOING)->count();
        $activitiesCompleted  = (clone $activities)->where('status', \App\Enums\ActivityStatus::COMPLETED)->count();
        $activitiesOverdue    = (clone $activities)->where('status', \App\Enums\ActivityStatus::OVERDUE)->count();

        $totalPlannedBudget = $budgets->sum('total_cost');
        $totalActualBudget  = 0;
        $budgetVariance     = $totalPlannedBudget - $totalActualBudget;

        $recentProgressUpdates = $updates->with(['project:id,title', 'activity:id,description', 'creator:id,name'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get(['id', 'project_id', 'activity_id', 'creator_user_id', 'date', 'progress_percentage']);

        $recentProjects = (clone $projects)->with('creator:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'title', 'status', 'created_at', 'creator_user_id']);

        $budgetByProject = (clone $projects)->limit(10)
            ->get(['id', 'title'])
            ->map(fn($p) => [
                'label' => \Illuminate\Support\Str::limit($p->title, 20),
                'value' => Budget::where('project_id', $p->id)->sum('total_cost')
            ])
            ->toArray();

        $statusDistribution = [
            ['label' => ProjectStatus::ACTIVE->label(),    'value' => $projectsInProgress, 'color' => ProjectStatus::ACTIVE->hex()],
            ['label' => ProjectStatus::COMPLETED->label(), 'value' => $projectsCompleted,  'color' => ProjectStatus::COMPLETED->hex()],
            ['label' => ProjectStatus::DRAFT->label(),     'value' => $projectsDraft,       'color' => ProjectStatus::DRAFT->hex()],
            ['label' => ProjectStatus::CANCELLED->label(), 'value' => $projectsCanceled,    'color' => ProjectStatus::CANCELLED->hex()],
        ];

        return compact(
            'totalProjects', 'projectsInProgress', 'projectsCompleted', 'projectsDraft', 'projectsCanceled',
            'totalActivities', 'activitiesInProgress', 'activitiesCompleted', 'activitiesOverdue',
            'totalPlannedBudget', 'totalActualBudget', 'budgetVariance',
            'recentProgressUpdates', 'recentProjects', 'budgetByProject', 'statusDistribution'
        );
    }
}
