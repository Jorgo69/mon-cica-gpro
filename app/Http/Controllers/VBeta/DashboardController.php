<?php

namespace App\Http\Controllers\VBeta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Activity;
use App\Models\Budget;
use App\Models\Risk;
use App\Models\ProgressTracker;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // public function adminIndex()
    // {
    //     // 1. Statistiques Générales des Projets
    //     $totalProjects = Project::count();
    //     $projectsInProgress = Project::where('status', 'in_progress')->count();
    //     $projectsCompleted = Project::where('status', 'completed')->count();
    //     $projectsPlanned = Project::where('status', 'planned')->count();
    //     $projectsCanceled = Project::where('status', 'canceled')->count();

    //     // 2. Statistiques des Activités
    //     $totalActivities = Activity::count();
    //     $activitiesInProgress = Activity::where('status', 'in_progress')->count();
    //     $activitiesCompleted = Activity::where('status', 'completed')->count();
    //     $activitiesNotStarted = Activity::where('status', 'not_started')->count();

    //     // 3. Vue d'ensemble du Budget (exemple simple)
    //     $totalPlannedBudget = Budget::sum('planned_amount');
    //     $totalActualBudget = Budget::sum('actual_amount');
    //     $budgetVariance = $totalPlannedBudget - $totalActualBudget;

    //     // 4. Risques ouverts
    //     $openRisks = Risk::where('status', 'open')->count();
    //     $highImpactRisks = Risk::where('status', 'open')->where('impact', 'high')->count();

    //     // 5. Mises à jour de progression récentes (les 5 dernières)
    //     $recentProgressUpdates = ProgressUpdate::with(['project', 'activity', 'updater'])
    //                                             ->orderBy('update_date', 'desc')
    //                                             ->limit(5)
    //                                             ->get();

    //     // 6. Projets récents (par exemple, les 5 derniers projets créés)
    //     $recentProjects = Project::orderBy('created_at', 'desc')->limit(5)->get();

    //     // Vous pouvez passer plus de données selon les besoins
    //     return view('v_beta.dahboard', [
    //         'totalProjects' => $totalProjects,
    //         'projectsInProgress' => $projectsInProgress,
    //         'projectsCompleted' => $projectsCompleted,
    //         'projectsPlanned' => $projectsPlanned,
    //         'projectsCanceled' => $projectsCanceled,

    //         'totalActivities' => $totalActivities,
    //         'activitiesInProgress' => $activitiesInProgress,
    //         'activitiesCompleted' => $activitiesCompleted,
    //         'activitiesNotStarted' => $activitiesNotStarted,

    //         'totalPlannedBudget' => $totalPlannedBudget,
    //         'totalActualBudget' => $totalActualBudget,
    //         'budgetVariance' => $budgetVariance,

    //         'openRisks' => $openRisks,
    //         'highImpactRisks' => $highImpactRisks,

    //         'recentProgressUpdates' => $recentProgressUpdates,
    //         'recentProjects' => $recentProjects,
    //     ]);
    // }

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized. Please log in.');
        }

        $isAdmin = $user->role === \App\Enums\AccountType::ADMIN;

        if ($isAdmin) {
            // Administrateur voit toutes les données
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

            $recentProgressUpdates = ProgressTracker::with(['project', 'activity', 'updater'])
                                                    ->orderBy('date', 'desc')
                                                    ->limit(5)
                                                    ->get();

            $recentProjects = Project::with('creator')->orderBy('created_at', 'desc')->limit(5)->get();

            return view('dashboard', compact(
                'isAdmin',
                'totalProjects',
                'projectsInProgress',
                'projectsCompleted',
                'projectsDraft',
                'projectsCanceled',
                'totalActivities',
                'activitiesInProgress',
                'activitiesCompleted',
                'activitiesOverdue',
                'totalPlannedBudget',
                'totalActualBudget',
                'budgetVariance',
                'recentProgressUpdates',
                'recentProjects'
            ));
        } else {
            // Utilisateur ne voit que les données qui le concernent
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
                                                    ->with(['project', 'activity', 'updater'])
                                                    ->orderBy('date', 'desc')
                                                    ->limit(5)
                                                    ->get();
            $recentProjects = $userProjects->sortByDesc('created_at')->take(5);

            return view('v_beta.dashboard', compact(
                'isAdmin',
                'totalProjects',
                'projectsInProgress',
                'projectsCompleted',
                'projectsDraft',
                'projectsCanceled',
                'totalActivities',
                'activitiesInProgress',
                'activitiesCompleted',
                'activitiesOverdue',
                'totalPlannedBudget',
                'totalActualBudget',
                'budgetVariance',
                'recentProgressUpdates',
                'recentProjects'
            ));
        }
    }

}