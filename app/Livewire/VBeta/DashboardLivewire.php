<?php

namespace App\Livewire\VBeta;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\Queries\ProjectStatsQueryService;
use App\Enums\AccountType;
use App\Models\Activity;

class DashboardLivewire extends Component
{
    public string $period = 'all'; // all, month, quarter, year

    public function mount()
    {
        $user = Auth::user();

        // ROOT sans impersonation -> son propre dashboard
        // ROOT dans une org (impersonation) -> dashboard org normal
        if ($user && $user->role === AccountType::ROOT && !session('acting_as_organization_id')) {
            $this->redirect(route('system.dashboard'));
        }
    }

    public function render(ProjectStatsQueryService $queryService)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized. Please log in.');
        }

        [$startDate, $endDate] = $this->getDateRange();
        
        $stats = $queryService->getDashboardStats($user, $startDate, $endDate);
        $isAdmin = in_array($user->role, [AccountType::ROOT, AccountType::ORG_ADMIN]);

        // Proactive Alerts: Overdue activities for this user/org
        $overdueActivities = Activity::query()
            ->where('status', \App\Enums\ActivityStatus::OVERDUE)
            ->when($user->role === AccountType::ORG_USER, fn($q) => $q->where('responsible_user_id', $user->id))
            ->with('project:id,title')
            ->limit(3)
            ->get();

        $viewData = array_merge([
            'isAdmin' => $isAdmin,
            'overdueActivities' => $overdueActivities,
            'currentPeriod' => $this->period,
        ], $stats);

        return view('livewire.v-beta.dashboard-livewire', $viewData);
    }

    public function setPeriod(string $period)
    {
        if (in_array($period, ['all', 'month', 'quarter', 'year'])) {
            $this->period = $period;
        }
    }

    private function getDateRange(): array
    {
        $now = now();
        return match ($this->period) {
            'month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'quarter' => [$now->copy()->startOfQuarter(), $now->copy()->endOfQuarter()],
            'year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            default => [null, null],
        };
    }


    public function placeholder()
    {
        return view('components.ui.skeleton-table');
    }
}
