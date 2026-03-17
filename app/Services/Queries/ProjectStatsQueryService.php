<?php

namespace App\Services\Queries;

use App\Models\Project;
use App\Models\Activity;
use App\Models\Budget;
use App\Models\ProgressTracker;

class ProjectStatsQueryService
{
    /**
     * Get the statistics for the dashboard according to the user's role and permissions.
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function getDashboardStats($user): array
    {
        // Les rôles IT_ADMIN, ORG_ADMIN, et MANAGER peuvent voir toutes les données de l'organisation
        $viewAll = $user->hasRole(['IT_ADMIN', 'ORG_ADMIN', 'MANAGER']);

        if ($viewAll) {
            return $this->getAllStats();
        }

        return $this->getUserSpecificStats($user);
    }

    private function getAllStats(): array
    {
        $totalProjects = Project::count();
        $projectsInProgress = Project::where('status', \App\Enums\ProjectStatus::ACTIVE)->count();
        $projectsCompleted = Project::where('status', \App\Enums\ProjectStatus::COMPLETED)->count();
        $projectsDraft = Project::where('status', \App\Enums\ProjectStatus::DRAFT)->count();
        $projectsCanceled = Project::where('status', \App\Enums\ProjectStatus::CANCELLED)->count();

        $totalActivities = Activity::count();
        $activitiesInProgress = Activity::where('status', \App\Enums\ActivityStatus::ONGOING)->count();
        $activitiesCompleted = Activity::where('status', \App\Enums\ActivityStatus::COMPLETED)->count();
        $activitiesOverdue = Activity::where('status', \App\Enums\ActivityStatus::OVERDUE)->count();

        $totalPlannedBudget = Budget::sum('total_cost');
        $totalActualBudget = 0; 
        $budgetVariance = $totalPlannedBudget - $totalActualBudget;

        $recentProgressUpdates = ProgressTracker::with(['project', 'activity', 'creator'])
                                                ->orderBy('date', 'desc')
                                                ->limit(5)
                                                ->get();

        $recentProjects = Project::with('creator')->orderBy('created_at', 'desc')->limit(5)->get();

        $budgetByProject = Project::limit(10)
            ->get()
            ->map(fn($p) => ['label' => \Illuminate\Support\Str::limit($p->title, 20), 'value' => \App\Models\Budget::where('project_id', $p->id)->sum('total_cost')])
            ->toArray();

        $statusDistribution = [
            ['label' => 'En cours', 'value' => $projectsInProgress, 'color' => '#10b981'],
            ['label' => 'Terminé', 'value' => $projectsCompleted, 'color' => '#3b82f6'],
            ['label' => 'Brouillon', 'value' => $projectsDraft, 'color' => '#94a3b8'],
            ['label' => 'Annulé', 'value' => $projectsCanceled, 'color' => '#ef4444'],
        ];

        return compact(
            'totalProjects', 'projectsInProgress', 'projectsCompleted', 'projectsDraft', 'projectsCanceled',
            'totalActivities', 'activitiesInProgress', 'activitiesCompleted', 'activitiesOverdue',
            'totalPlannedBudget', 'totalActualBudget', 'budgetVariance',
            'recentProgressUpdates', 'recentProjects', 'budgetByProject', 'statusDistribution'
        );
    }

    private function getUserSpecificStats($user): array
    {
        $userProjects = Project::where('creator_user_id', $user->id)
                               ->orWhereHas('logicalFramework.specificObjectives.results.activities', function ($query) use ($user) {
                                   $query->where('responsible_user_id', $user->id);
                               })
                               ->distinct()
                               ->get();

        $totalProjects = $userProjects->count();
        $projectsInProgress = $userProjects->where('status', \App\Enums\ProjectStatus::ACTIVE)->count();
        $projectsCompleted = $userProjects->where('status', \App\Enums\ProjectStatus::COMPLETED)->count();
        $projectsDraft = $userProjects->where('status', \App\Enums\ProjectStatus::DRAFT)->count();
        $projectsCanceled = $userProjects->where('status', \App\Enums\ProjectStatus::CANCELLED)->count();

        $userActivities = Activity::where('responsible_user_id', $user->id)->get();
        $totalActivities = $userActivities->count();
        $activitiesInProgress = $userActivities->where('status', \App\Enums\ActivityStatus::ONGOING)->count();
        $activitiesCompleted = $userActivities->where('status', \App\Enums\ActivityStatus::COMPLETED)->count();
        $activitiesOverdue = $userActivities->where('status', \App\Enums\ActivityStatus::OVERDUE)->count();

        $projectIds = $userProjects->pluck('id');
        $totalPlannedBudget = Budget::whereIn('project_id', $projectIds)->sum('total_cost');
        $totalActualBudget = 0;
        $budgetVariance = $totalPlannedBudget - $totalActualBudget;

        $recentProgressUpdates = ProgressTracker::whereIn('project_id', $projectIds)
                                                ->with(['project', 'activity', 'creator'])
                                                ->orderBy('date', 'desc')
                                                ->limit(5)
                                                ->get();

        $recentProjects = $userProjects->sortByDesc('created_at')->take(5);

        $budgetByProject = $userProjects->take(10)
            ->map(fn($p) => ['label' => \Illuminate\Support\Str::limit($p->title, 20), 'value' => \App\Models\Budget::where('project_id', $p->id)->sum('total_cost')])
            ->values()
            ->toArray();

        $statusDistribution = [
            ['label' => 'En cours', 'value' => $projectsInProgress, 'color' => '#10b981'],
            ['label' => 'Terminé', 'value' => $projectsCompleted, 'color' => '#3b82f6'],
            ['label' => 'Brouillon', 'value' => $projectsDraft, 'color' => '#94a3b8'],
            ['label' => 'Annulé', 'value' => $projectsCanceled, 'color' => '#ef4444'],
        ];

        return compact(
            'totalProjects', 'projectsInProgress', 'projectsCompleted', 'projectsDraft', 'projectsCanceled',
            'totalActivities', 'activitiesInProgress', 'activitiesCompleted', 'activitiesOverdue',
            'totalPlannedBudget', 'totalActualBudget', 'budgetVariance',
            'recentProgressUpdates', 'recentProjects', 'budgetByProject', 'statusDistribution'
        );
    }
}
