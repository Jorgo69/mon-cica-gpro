<?php

namespace App\Http\Controllers;

use App\Models\ShareToken;
use App\Services\Queries\LogframeQueryService;

class SharedProjectController extends Controller
{
    public function show(string $token)
    {
        $shareToken = ShareToken::where('token', $token)->firstOrFail();

        if (! $shareToken->isValid()) {
            abort(403, __('shared.link_expired'));
        }

        $shareToken->recordView();

        $project = LogframeQueryService::forProject($shareToken->project_id)->project();

        if (! $project) {
            abort(404);
        }

        $activities = $project->getAllActivities();

        $stats = [
            'total_activities' => $activities->count(),
            'completed' => $activities->where('status', \App\Enums\ActivityStatus::COMPLETED)->count(),
            'in_progress' => $activities->where('status', \App\Enums\ActivityStatus::ONGOING)->count(),
            'not_started' => $activities->where('status', \App\Enums\ActivityStatus::DRAFT)->count(),
            'overdue' => $activities->filter(fn ($a) => $a->end_date?->isPast() && $a->status !== \App\Enums\ActivityStatus::COMPLETED)->count(),
            'progress' => $project->calculateProjectProgress(),
        ];

        $budgetPlanned = $project->budgets->sum('total_cost');
        $budgetSpent = $project->expenses->sum('amount');

        $budget = [
            'planned' => $budgetPlanned,
            'spent' => $budgetSpent,
            'remaining' => $budgetPlanned - $budgetSpent,
            'percent' => $budgetPlanned > 0 ? round(($budgetSpent / $budgetPlanned) * 100, 1) : 0,
        ];

        return view('shared.project-dashboard', compact(
            'project', 'shareToken', 'stats', 'budget', 'activities'
        ));
    }
}
