<div>
    <x-ui.page-layout>
        {{-- En-tête de la page --}}
        <x-ui.page-header :title="__('dashboard.title')" :subtitle="__('dashboard.subtitle')">
            <x-slot:actions>
                <div class="flex items-center gap-2 mr-4 bg-surface-alt p-1 rounded-xl">
                    @foreach(['all' => __('dashboard.global'), 'month' => __('dashboard.month'), 'quarter' => __('dashboard.quarter'), 'year' => __('dashboard.year')] as $key => $label)
                        <button 
                            wire:click="setPeriod('{{ $key }}')"
                            class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $currentPeriod === $key ? 'bg-card text-primary shadow-sm' : 'text-subtle hover:text-heading' }}"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
                <x-ui.button tag="a" :href="route('project.create')" variant="accent" icon="plus" size="md" wire:navigate>
                    {{ __('dashboard.new_project') }}
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        {{-- Alertes Critiques --}}
        @if($overdueActivities->isNotEmpty())
            <div class="mb-8 p-4 rounded-2xl bg-error/5 border border-error/10 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-error/10 flex items-center justify-center shrink-0">
                        <x-lucide-alert-circle class="w-5 h-5 text-error" />
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-error uppercase tracking-wider">{{ __('dashboard.overdue_activities') }}</h3>
                        <p class="text-xs text-subtle">{{ __('dashboard.overdue_alert', ['count' => $overdueActivities->count()]) }}</p>
                    </div>
                </div>
                <div class="flex -space-x-2">
                    @foreach($overdueActivities as $activity)
                        <div class="w-8 h-8 rounded-lg bg-card border-2 border-surface dark:border-surface-alt flex items-center justify-center" title="{{ $activity->project->title }}">
                            <span class="text-[10px] font-bold text-error">!</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Statistiques Globales --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-ui.stat-card :label="__('dashboard.total_projects')" :value="$totalProjects" variant="default" icon="folder" />
            <x-ui.stat-card :label="__('dashboard.in_progress')" :value="$projectsInProgress" variant="success" icon="play-circle" />
            <x-ui.stat-card :label="__('dashboard.completed')" :value="$projectsCompleted" variant="accent" icon="check-circle" />
            <x-ui.stat-card :label="__('dashboard.cancelled')" :value="$projectsCanceled" variant="error" icon="x-circle" />
        </div>


        {{-- Section Analytique : Graphiques --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <x-ui.section :title="__('dashboard.budget_per_project')" icon="bar-chart-3">
                <x-ui.chart 
                    type="bar" 
                    height="320px"
                    :labels="collect($budgetByProject)->pluck('label')->toArray()"
                    :datasets="[
                        [
                            'label' => __('dashboard.planned_budget') . ' (FCFA)',
                            'data' => collect($budgetByProject)->pluck('value')->toArray(),
                            'backgroundColor' => 'rgba(99, 102, 241, 0.2)',
                            'borderColor' => 'rgb(99, 102, 241)',
                            'borderWidth' => 2,
                            'borderRadius' => 8,
                            'hoverBackgroundColor' => 'rgba(99, 102, 241, 0.4)',
                        ]
                    ]"
                />
            </x-ui.section>

            <x-ui.section :title="__('dashboard.projects_overview')" icon="pie-chart">
                <x-ui.chart 
                    type="doughnut" 
                    height="320px"
                    :labels="collect($statusDistribution)->pluck('label')->toArray()"
                    :datasets="[
                        [
                            'data' => collect($statusDistribution)->pluck('value')->toArray(),
                            'backgroundColor' => collect($statusDistribution)->pluck('color')->toArray(),
                            'borderWidth' => 0,
                            'hoverOffset' => 10
                        ]
                    ]"
                    :options="[
                        'plugins' => [
                            'legend' => [ 'position' => 'right' ]
                        ],
                        'cutout' => '70%'
                    ]"
                />
            </x-ui.section>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Colonne de gauche : Activités --}}
            <div class="lg:col-span-2 space-y-8">
                <x-ui.section :title="__('dashboard.activity_status')" icon="activity">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="p-4 rounded-2xl bg-surface dark:bg-surface-alt/50 border border-border-light">
                            <p class="text-[10px] font-black text-muted uppercase tracking-widest mb-1">{{ __('dashboard.ongoing') }}</p>
                            <p class="text-2xl font-black text-primary">{{ $activitiesInProgress }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-surface dark:bg-surface-alt/50 border border-border-light">
                            <p class="text-[10px] font-black text-muted uppercase tracking-widest mb-1">{{ __('dashboard.completed') }}</p>
                            <p class="text-2xl font-black text-success">{{ $activitiesCompleted }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-surface dark:bg-surface-alt/50 border border-border-light">
                            <p class="text-[10px] font-black text-muted uppercase tracking-widest mb-1">{{ __('dashboard.overdue') }}</p>
                            <p class="text-2xl font-black text-error">{{ $activitiesOverdue }}</p>
                        </div>
                    </div>
                </x-ui.section>

                <x-ui.section :title="__('dashboard.recent_updates')" icon="history">
                    <div class="space-y-4">
                        @forelse($recentProgressUpdates as $update)
                            <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-surface dark:hover:bg-surface-alt/50 transition-colors border border-transparent hover:border-border-light">
                                <div class="w-10 h-10 rounded-full bg-surface-alt flex items-center justify-center shrink-0">
                                    <x-lucide-user class="w-5 h-5 text-muted" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-heading truncate">{{ $update->project?->title ?? __('dashboard.unknown_project') }}</p>
                                    <p class="text-xs text-subtle truncate">{{ $update->activity?->description ?? __('dashboard.deleted_activity') }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-[10px] font-black text-muted uppercase text-right">{{ $update->date->diffForHumans() }}</p>
                                    <p class="text-xs font-bold text-accent">{{ $update->progress_percentage }}%</p>
                                </div>
                            </div>
                        @empty
                            <x-ui.empty-state icon="clock" :title="__('dashboard.no_updates')" :description="__('dashboard.no_updates_desc')" />
                        @endforelse
                    </div>
                </x-ui.section>
            </div>

            {{-- Colonne de droite : Projets Récents --}}
            <div class="space-y-8">
                <x-ui.section :title="__('dashboard.recent_projects')" icon="folder-closed">
                    <div class="space-y-4">
                        @forelse($recentProjects as $project)
                            <div class="p-4 rounded-xl bg-card border border-border-light shadow-sm hover:shadow-md transition-all">
                                <h4 class="text-sm font-bold text-heading mb-1 line-clamp-1">{{ $project->title }}</h4>
                                <p class="text-[10px] text-subtle mb-3 flex items-center gap-1">
                                    <x-lucide-calendar class="w-3 h-3" />
                                    {{ __('dashboard.created_on') }} {{ $project->created_at->format('d/m/Y') }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <x-ui.badge :variant="$project->status?->color() ?? 'slate'" size="sm">
                                        {{ $project->status?->label() ?? $project->status }}
                                    </x-ui.badge>
                                    <x-ui.button tag="a" :href="route('project.show', $project->id)" variant="ghost" size="sm" icon="arrow-right" wire:navigate />
                                </div>
                            </div>
                        @empty
                            <x-ui.empty-state icon="folder-open" :title="__('dashboard.no_projects')" :description="__('dashboard.no_projects_desc')" />
                        @endforelse
                    </div>
                </x-ui.section>
            </div>
        </div>
    </x-ui.page-layout>
</div>
