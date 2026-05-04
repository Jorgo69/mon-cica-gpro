<x-app-layout>
    <x-ui.page-layout>
        {{-- En-tête de la page --}}
        <x-ui.page-header :title="__('Dashboard')" :subtitle="__('Overview of project performance')">
            <x-slot:actions>
                <x-ui.button tag="a" :href="route('project.create')" variant="accent" icon="plus" size="lg">
                    {{ __('Create project') }}
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        {{-- Statistiques Globales --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-ui.stat-card :label="__('Total Projects')" :value="$totalProjects" variant="default" icon="folder" />
            <x-ui.stat-card :label="__('Active')" :value="$projectsInProgress" variant="success" icon="play-circle" />
            <x-ui.stat-card :label="__('Completed')" :value="$projectsCompleted" variant="accent" icon="check-circle" />
            <x-ui.stat-card :label="__('Cancelled')" :value="$projectsCanceled" variant="error" icon="x-circle" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Colonne de gauche : Activités --}}
            <div class="lg:col-span-2 space-y-8">
                <x-ui.section :title="__('Activity Status')" icon="activity">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="p-4 rounded-2xl bg-surface dark:bg-surface-alt/50 border border-border-light">
                            <p class="text-[10px] font-black text-muted uppercase tracking-widest mb-1">{{ __('Ongoing') }}</p>
                            <p class="text-2xl font-black text-primary">{{ $activitiesInProgress }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-surface dark:bg-surface-alt/50 border border-border-light">
                            <p class="text-[10px] font-black text-muted uppercase tracking-widest mb-1">{{ __('Completed') }}</p>
                            <p class="text-2xl font-black text-success">{{ $activitiesCompleted }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-surface dark:bg-surface-alt/50 border border-border-light">
                            <p class="text-[10px] font-black text-muted uppercase tracking-widest mb-1">{{ __('Overdue') }}</p>
                            <p class="text-2xl font-black text-error">{{ $activitiesOverdue }}</p>
                        </div>
                    </div>
                </x-ui.section>

                <x-ui.section :title="__('Recent updates')" icon="history">
                    <div class="space-y-4">
                        @forelse($recentProgressUpdates as $update)
                            <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-surface dark:hover:bg-surface-alt/50 transition-colors border border-transparent hover:border-border-light">
                                <div class="w-10 h-10 rounded-full bg-surface-alt flex items-center justify-center shrink-0">
                                    <x-lucide-user class="w-5 h-5 text-muted" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-heading truncate">{{ $update->project->title }}</p>
                                    <p class="text-xs text-subtle truncate">{{ $update->activity->description }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-[10px] font-black text-muted uppercase text-right">{{ $update->date->diffForHumans() }}</p>
                                    <p class="text-xs font-bold text-accent">{{ $update->progress_percentage }}%</p>
                                </div>
                            </div>
                        @empty
                            <x-ui.empty-state icon="clock" :title="__('No updates')" :description="__('Activities have not been updated yet.')" />
                        @endforelse
                    </div>
                </x-ui.section>
            </div>

            {{-- Colonne de droite : Projets Récents --}}
            <div class="space-y-8">
                <x-ui.section :title="__('Recent Projects')" icon="folder-closed">
                    <div class="space-y-4">
                        @forelse($recentProjects as $project)
                            <div class="p-4 rounded-xl bg-card border border-border-light shadow-sm hover:shadow-md transition-all">
                                <h4 class="text-sm font-bold text-heading mb-1 line-clamp-1">{{ $project->title }}</h4>
                                <p class="text-[10px] text-subtle mb-3 flex items-center gap-1">
                                    <x-lucide-calendar class="w-3 h-3" />
                                    {{ __('Created on') }} {{ $project->created_at->format('d/m/Y') }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <x-ui.badge :variant="$project->status?->color() ?? 'slate'" size="sm">
                                        {{ $project->status?->label() ?? $project->status }}
                                    </x-ui.badge>
                                    <x-ui.button tag="a" :href="route('project.show', $project->id)" variant="ghost" size="sm" icon="arrow-right" />
                                </div>
                            </div>
                        @empty
                            <x-ui.empty-state icon="folder-open" :title="__('No projects yet')" :description="__('Start by creating a new project.')" />
                        @endforelse
                    </div>
                </x-ui.section>
            </div>
        </div>
    </x-ui.page-layout>
</x-app-layout>