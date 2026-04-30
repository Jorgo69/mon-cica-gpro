<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\Project;

class BudgetTrackingService
{
    /**
     * Get budget summary for a project.
     */
    public static function projectSummary(Project $project): array
    {
        $totalPlanned = (float) $project->budgets()->sum('total_cost');
        $totalSpent = (float) Expense::where('project_id', $project->id)->sum('amount');
        $remaining = $totalPlanned - $totalSpent;
        $usedPercent = $totalPlanned > 0 ? round(($totalSpent / $totalPlanned) * 100, 1) : 0;

        return [
            'planned' => $totalPlanned,
            'spent' => $totalSpent,
            'remaining' => $remaining,
            'used_percent' => $usedPercent,
            'is_over_budget' => $totalSpent > $totalPlanned,
            'currency' => $project->currency,
        ];
    }

    /**
     * Get budget summary per budget line.
     */
    public static function budgetLineSummaries(Project $project): array
    {
        $budgets = $project->budgets()->get();
        $summaries = [];

        foreach ($budgets as $budget) {
            $spent = (float) Expense::where('budget_id', $budget->id)->sum('amount');
            $planned = (float) $budget->total_cost;
            $remaining = $planned - $spent;
            $usedPercent = $planned > 0 ? round(($spent / $planned) * 100, 1) : 0;

            $summaries[] = [
                'budget' => $budget,
                'planned' => $planned,
                'spent' => $spent,
                'remaining' => $remaining,
                'used_percent' => $usedPercent,
                'status' => self::budgetStatus($usedPercent),
            ];
        }

        return $summaries;
    }

    /**
     * Get burn rate (average daily spending).
     */
    public static function burnRate(Project $project): array
    {
        $expenses = Expense::where('project_id', $project->id)
            ->orderBy('expense_date')
            ->get();

        if ($expenses->isEmpty()) {
            return ['daily' => 0, 'monthly' => 0, 'projected_total' => 0, 'days_remaining' => null];
        }

        $firstDate = $expenses->first()->expense_date;
        $lastDate = $expenses->last()->expense_date;
        $totalSpent = (float) $expenses->sum('amount');
        $daySpan = max(1, (int) $firstDate->diffInDays($lastDate) + 1);

        $dailyRate = $totalSpent / $daySpan;
        $monthlyRate = $dailyRate * 30;

        $totalPlanned = (float) $project->budgets()->sum('total_cost');
        $remaining = $totalPlanned - $totalSpent;
        $daysRemaining = $dailyRate > 0 ? (int) ceil($remaining / $dailyRate) : null;

        // Projected total at end of project
        $projectDaysLeft = $project->end_date ? max(0, (int) now()->diffInDays($project->end_date)) : 0;
        $projectedTotal = $totalSpent + ($dailyRate * $projectDaysLeft);

        return [
            'daily' => round($dailyRate, 2),
            'monthly' => round($monthlyRate, 2),
            'projected_total' => round($projectedTotal, 2),
            'days_remaining' => $daysRemaining,
        ];
    }

    /**
     * Spending by category.
     */
    public static function spendingByCategory(Project $project): array
    {
        return Expense::where('project_id', $project->id)
            ->selectRaw("COALESCE(category, 'Non categorise') as category, SUM(amount) as total")
            ->groupBy('category')
            ->orderByDesc('total')
            ->pluck('total', 'category')
            ->toArray();
    }

    private static function budgetStatus(float $usedPercent): string
    {
        if ($usedPercent >= 100) return 'over';
        if ($usedPercent >= 80) return 'warning';
        if ($usedPercent >= 50) return 'normal';
        return 'good';
    }
}
