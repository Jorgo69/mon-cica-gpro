<?php

namespace App\Livewire\VBeta;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\Queries\ProjectStatsQueryService;
use App\Enums\AccountType;
use App\Models\Activity;
use Livewire\Attributes\Lazy;

#[Lazy]
class DashboardLivewire extends Component
{
    public string $period = 'all'; // all, month, quarter, year

    public function render(ProjectStatsQueryService $queryService)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized. Please log in.');
        }

        [$startDate, $endDate] = $this->getDateRange();
        
        $stats = $queryService->getDashboardStats($user, $startDate, $endDate);
        $isAdmin = in_array($user->role, [AccountType::SYSTEM_ADMIN->value, AccountType::ORG_ADMIN->value]);

        // Proactive Alerts: Overdue activities for this user/org
        $overdueActivities = Activity::query()
            ->where('status', \App\Enums\ActivityStatus::OVERDUE)
            ->where('organization_id', $user->organization_id)
            ->when($user->role === AccountType::ORG_USER->value, fn($q) => $q->where('responsible_user_id', $user->id))
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
        return <<<'HTML'
        <div>
            {{-- En-tête de la page SKELETON --}}
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <x-ui.skeleton type="block" class="w-48 h-8 mb-2" />
                    <x-ui.skeleton type="text" class="w-64" />
                </div>
                <x-ui.skeleton type="block" class="w-32 h-12" />
            </div>

            {{-- Statistiques Globales SKELETON --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-ui.skeleton type="card" class="h-32" />
                <x-ui.skeleton type="card" class="h-32" />
                <x-ui.skeleton type="card" class="h-32" />
                <x-ui.skeleton type="card" class="h-32" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Colonne de gauche SKELETON --}}
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                        <x-ui.skeleton type="block" class="w-40 h-6 mb-6" />
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <x-ui.skeleton type="card" class="h-24" />
                            <x-ui.skeleton type="card" class="h-24" />
                            <x-ui.skeleton type="card" class="h-24" />
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                        <x-ui.skeleton type="block" class="w-48 h-6 mb-6" />
                        <div class="space-y-4">
                            @for ($i = 0; $i < 4; $i++)
                                <div class="flex items-center gap-4">
                                    <x-ui.skeleton type="avatar" />
                                    <div class="flex-1">
                                        <x-ui.skeleton type="text" class="w-3/4 mb-1" />
                                        <x-ui.skeleton type="text" class="w-1/2 h-3" />
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                {{-- Colonne de droite SKELETON --}}
                <div class="space-y-8">
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                        <x-ui.skeleton type="block" class="w-40 h-6 mb-6" />
                        <div class="space-y-4">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="border border-slate-100 dark:border-slate-800 rounded-xl p-4">
                                    <x-ui.skeleton type="text" class="w-full mb-3 h-5" />
                                    <x-ui.skeleton type="text" class="w-1/2 mb-4 h-3" />
                                    <div class="flex justify-between items-center">
                                        <x-ui.skeleton type="block" class="w-20 h-6 rounded-full" />
                                        <x-ui.skeleton type="avatar" class="w-8 h-8 rounded-lg" />
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }
}
