<x-ui.page-layout>

    {{-- Page Header --}}
    <x-ui.page-header :title="__('projects.dashboard.title', ['name' => $project->title])" :subtitle="__('projects.dashboard.subtitle')" />

    {{-- Progress Bar --}}
    @include('livewire.v1.project.include.progres-bar')

    {{-- Stat Cards --}}
    @php
        $totalActivitiesCount = $allActivities->count();
        $completedActivitiesCount = $allActivities->filter(fn($a) => $a->calculateProgress() >= 100)->count();
        $nonStartedCount = $allActivities->filter(fn($a) => $a->calculateProgress() === 0)->count();
        $ongoingCount = $allActivities->filter(fn($a) => $a->calculateProgress() > 0 && $a->calculateProgress() < 100)->count();
        $lateActivitiesCount = $allActivities->filter(function ($activity) {
            $plannedProgress = $activity->getPlannedProgressPercentage();
            $actualProgress = $activity->calculateProgress();
            return $actualProgress < $plannedProgress && $plannedProgress > 0;
        })->count();
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 my-6">
        <x-ui.stat-card :value="$totalActivitiesCount" :label="__('projects.dashboard.total')" icon="layers" variant="default" />
        <x-ui.stat-card :value="$completedActivitiesCount" :label="__('projects.dashboard.completed')" icon="check-circle-2" variant="success" />
        <x-ui.stat-card :value="$ongoingCount" :label="__('projects.dashboard.in_progress')" icon="loader" variant="accent" />
        <x-ui.stat-card :value="$nonStartedCount" :label="__('projects.dashboard.not_started')" icon="clock" variant="warning" />
        <x-ui.stat-card :value="$lateActivitiesCount" :label="__('projects.dashboard.overdue')" icon="alert-triangle" variant="error" />
    </div>

    {{-- Filters --}}
    <x-ui.card class="mb-6" :noPadding="false">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-ui.input 
                wire:model.live.debounce.300ms="search" 
                :placeholder="__('projects.dashboard.search_placeholder')"
                icon="search" />

            <x-ui.select wire:model.live="responsibleUserFilter" icon="user">
                <option value="">{{ __('projects.dashboard.all_responsibles') }}</option>
                @foreach ($availableUsers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </x-ui.select>

            <x-ui.select wire:model.live="statusFilter" icon="filter">
                <option value="">{{ __('projects.dashboard.all_statuses') }}</option>
                <option value="En cours">{{ __('common.in_progress') }}</option>
                <option value="Terminé">{{ __('common.completed') }}</option>
                <option value="En retard">{{ __('common.overdue') }}</option>
            </x-ui.select>

            <x-ui.select wire:model.live="perPage" icon="list">
                @if ($totalActivitiesCount >= 10)
                    <option value="10">10 {{ __('projects.dashboard.per_page') }}</option>
                @endif
                @if ($totalActivitiesCount >= 25)
                    <option value="25">25 {{ __('projects.dashboard.per_page') }}</option>
                @endif
                @if ($totalActivitiesCount >= 50)
                    <option value="50">50 {{ __('projects.dashboard.per_page') }}</option>
                @endif
                <option value="{{ $totalActivitiesCount }}">{{ __('common.all') }}</option>
            </x-ui.select>
        </div>
    </x-ui.card>

    {{-- Activities Table --}}
    <x-ui.section :title="__('projects.dashboard.activity_details')" icon="clipboard-list">
        <div class="overflow-x-auto -mx-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-border-light dark:border-surface-alt">
                        <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">{{ __('projects.dashboard.description') }}</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">{{ __('projects.dashboard.responsible') }}</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">{{ __('projects.dashboard.status') }}</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">{{ __('projects.dashboard.progress') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-black text-muted uppercase tracking-widest">{{ __('projects.dashboard.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light dark:divide-surface-alt/50">
                    @forelse ($activities as $activity)
                        <tr class="hover:bg-surface/50 dark:hover:bg-surface-alt/30 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-heading">{{ $activity->description }}</td>
                            <td class="px-6 py-4 text-sm text-subtle">{{ $activity->responsibleUser->name ?? 'Non assigné' }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColor = match($activity->status) {
                                        'Terminé' => 'success',
                                        'En cours' => 'accent',
                                        'En retard' => 'error',
                                        default => 'slate',
                                    };
                                @endphp
                                <x-ui.badge :variant="$statusColor" size="sm">{{ $activity->status }}</x-ui.badge>
                            </td>
                            <td class="px-6 py-4">
                                @php $progress = $activity->calculateProgress(); @endphp
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-1.5 bg-surface-alt rounded-full overflow-hidden">
                                        <div class="h-full bg-accent rounded-full transition-all" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <span class="text-[11px] font-bold text-subtle">{{ $progress }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="openActivityDetails('{{ $activity->id }}')" class="navbar-action" title="{{ __('common.view') }}">
                                        <x-lucide-eye class="nav-icon" />
                                    </button>
                                    <a href="{{ route('activity.management', ['activity' => $activity->id]) }}" class="navbar-action" title="Gérer" wire:navigate>
                                        <x-lucide-settings-2 class="nav-icon" />
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-ui.empty-state icon="clipboard-x" :title="__('projects.dashboard.no_activities')" :description="__('projects.dashboard.no_activities_desc')" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-slot:footer>
            {{ $activities->links() }}
        </x-slot:footer>
    </x-ui.section>

    {{-- Activity Details Modal --}}
    @if($selectedActivityId)
        @livewire('v1.project.project-dashboard-activity-show-livewire', ['activityId' => $selectedActivityId])
    @endif

</x-ui.page-layout>
