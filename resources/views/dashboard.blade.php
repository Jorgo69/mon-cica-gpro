<x-app-layout>
    <x-ui.page-layout>
        {{-- En-tête de la page --}}
        <x-ui.page-header title="Tableau de Bord (Admin)" subtitle="Vue d'ensemble de la performance des projets">
            <x-slot:actions>
                <x-ui.button tag="a" :href="route('admin.project.types.create')" variant="accent" icon="plus" size="lg">
                    Créer un nouveau type
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        {{-- Statistiques Globales --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-ui.stat-card label="Total Projets" :value="$totalProjects" variant="default" icon="folder" />
            <x-ui.stat-card label="En Cours" :value="$projectsInProgress" variant="success" icon="play-circle" />
            <x-ui.stat-card label="Terminés" :value="$projectsCompleted" variant="accent" icon="check-circle" />
            <x-ui.stat-card label="Annulés" :value="$projectsCanceled" variant="error" icon="x-circle" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Colonne de gauche : Activités --}}
            <div class="lg:col-span-2 space-y-8">
                <x-ui.section title="Statut des Activités" icon="activity">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">En Cours</p>
                            <p class="text-2xl font-black text-primary">{{ $activitiesInProgress }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Terminées</p>
                            <p class="text-2xl font-black text-success">{{ $activitiesCompleted }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">En Retard</p>
                            <p class="text-2xl font-black text-error">{{ $activitiesOverdue }}</p>
                        </div>
                    </div>
                </x-ui.section>

                <x-ui.section title="Mises à jour récentes" icon="history">
                    <div class="space-y-4">
                        @forelse($recentProgressUpdates as $update)
                            <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border border-transparent hover:border-slate-100 dark:hover:border-slate-800">
                                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0">
                                    <x-lucide-user class="w-5 h-5 text-slate-400" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate">{{ $update->project->title }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $update->activity->description }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-[10px] font-black text-slate-400 uppercase">{{ $update->date->diffForHumans() }}</p>
                                    <p class="text-xs font-bold text-accent">{{ $update->progress_percentage }}%</p>
                                </div>
                            </div>
                        @empty
                            <x-ui.empty-state icon="clock" title="Aucune mise à jour" description="Les activités n'ont pas encore été mises à jour." />
                        @endforelse
                    </div>
                </x-ui.section>
            </div>

            {{-- Colonne de droite : Projets Récents --}}
            <div class="space-y-8">
                <x-ui.section title="Projets Récents" icon="folder-closed">
                    <div class="space-y-4">
                        @forelse($recentProjects as $project)
                            <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1 line-clamp-1">{{ $project->title }}</h4>
                                <p class="text-[10px] text-slate-500 mb-3 flex items-center gap-1">
                                    <x-lucide-calendar class="w-3 h-3" />
                                    Créé le {{ $project->created_at->format('d/m/Y') }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <x-ui.badge :variant="$project->status?->color() ?? 'slate'" size="sm">
                                        {{ $project->status?->label() ?? $project->status }}
                                    </x-ui.badge>
                                    <x-ui.button tag="a" :href="route('project.show', $project->id)" variant="ghost" size="sm" icon="arrow-right" />
                                </div>
                            </div>
                        @empty
                            <x-ui.empty-state icon="folder-open" title="Pas encore de projets" description="Commencez par créer un nouveau projet." />
                        @endforelse
                    </div>
                </x-ui.section>
            </div>
        </div>
    </x-ui.page-layout>
</x-app-layout>
