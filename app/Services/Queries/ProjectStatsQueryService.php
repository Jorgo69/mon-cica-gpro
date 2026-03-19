<?php

namespace App\Services\Queries;

use App\Models\Project;
use App\Models\Activity;
use App\Models\Budget;
use App\Models\ProgressTracker;
use App\Enums\AccountType;
use App\Enums\ProjectStatus;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ProjectStatsQueryService
{
    /**
     * Get the statistics for the dashboard according to the user's role and organization.
     */
    public function getDashboardStats($user, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $role = $user->role;
        $orgId = $user->organization_id;

        // Base query for projects and activities
        $projectQuery = Project::query();
        $activityQuery = Activity::query();
        $budgetQuery = Budget::query();
        $progressQuery = ProgressTracker::query();

        // Strict Multi-tenancy Isolation
        if ($role !== AccountType::SYSTEM_ADMIN->value) {
            $projectQuery->where('organization_id', $orgId);
            $activityQuery->where('organization_id', $orgId);
            $progressQuery->where('organization_id', $orgId);
            // Assuming Budget is linked to project
            $budgetQuery->whereHas('project', fn($q) => $q->where('organization_id', $orgId));
        }

        // Apply Date Filtering
        if ($startDate && $endDate) {
            $projectQuery->whereBetween('created_at', [$startDate, $endDate]);
            $activityQuery->whereBetween('created_at', [$startDate, $endDate]);
            $progressQuery->whereBetween('date', [$startDate, $endDate]);
        }

        // Additional filter for regular users (Collaborators)
        if ($role === AccountType::ORG_USER->value) {
            $this->applyUserFilters($user, $projectQuery, $activityQuery);
        }

        return $this->formatStats(
            $projectQuery, 
            $activityQuery, 
            $budgetQuery, 
            $progressQuery
        );
    }

    private function applyUserFilters($user, Builder $projectQuery, Builder $activityQuery): void
    {
        // Only show projects where user is creator or has assigned activities
        $projectQuery->where(function ($q) use ($user) {
            $q->where('creator_user_id', $user->id)
              ->orWhereHas('logicalFramework.specificObjectives.results.activities', fn($sq) => $sq->where('responsible_user_id', $user->id));
        });

        // Only show activities where user is responsible
        $activityQuery->where('responsible_user_id', $user->id);
    }

    private function formatStats(Builder $projects, Builder $activities, Builder $budgets, Builder $progress): array
    {
        // Use clone to avoid modifying original queries for different counts
        $totalProjects = (clone $projects)->count();
        $projectsInProgress = (clone $projects)->where('status', \App\Enums\ProjectStatus::ACTIVE)->count();
        $projectsCompleted = (clone $projects)->where('status', \App\Enums\ProjectStatus::COMPLETED)->count();
        $projectsDraft = (clone $projects)->where('status', \App\Enums\ProjectStatus::DRAFT)->count();
        $projectsCanceled = (clone $projects)->where('status', \App\Enums\ProjectStatus::CANCELLED)->count();

        $totalActivities = (clone $activities)->count();
        $activitiesInProgress = (clone $activities)->where('status', \App\Enums\ActivityStatus::ONGOING)->count();
        $activitiesCompleted = (clone $activities)->where('status', \App\Enums\ActivityStatus::COMPLETED)->count();
        $activitiesOverdue = (clone $activities)->where('status', \App\Enums\ActivityStatus::OVERDUE)->count();

        $totalPlannedBudget = $budgets->sum('total_cost');
        $totalActualBudget = 0; 
        $budgetVariance = $totalPlannedBudget - $totalActualBudget;

        // Minimal payload: only select necessary columns
        $recentProgressUpdates = $progress->with(['project:id,title', 'activity:id,description', 'creator:id,name'])
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
            ['label' => ProjectStatus::ACTIVE->label(), 'value' => $projectsInProgress, 'color' => ProjectStatus::ACTIVE->hex()],
            ['label' => ProjectStatus::COMPLETED->label(), 'value' => $projectsCompleted, 'color' => ProjectStatus::COMPLETED->hex()],
            ['label' => ProjectStatus::DRAFT->label(), 'value' => $projectsDraft, 'color' => ProjectStatus::DRAFT->hex()],
            ['label' => ProjectStatus::CANCELLED->label(), 'value' => $projectsCanceled, 'color' => ProjectStatus::CANCELLED->hex()],
        ];

        return compact(
            'totalProjects', 'projectsInProgress', 'projectsCompleted', 'projectsDraft', 'projectsCanceled',
            'totalActivities', 'activitiesInProgress', 'activitiesCompleted', 'activitiesOverdue',
            'totalPlannedBudget', 'totalActualBudget', 'budgetVariance',
            'recentProgressUpdates', 'recentProjects', 'budgetByProject', 'statusDistribution'
        );
    }
}
