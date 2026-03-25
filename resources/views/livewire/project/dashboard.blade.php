<x-ui.page-layout>

    {{-- Page Header --}}
    <x-ui.page-header title="Tableau de bord : {{ $project->title }}" subtitle="Suivi de l'avancement des activités du projet" />

    {{-- Progress Bar --}}
    @include('livewire.project.partials.progress-bar')

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
        <x-ui.stat-card :value="$totalActivitiesCount" label="Total" icon="layers" variant="default" />
        <x-ui.stat-card :value="$completedActivitiesCount" label="Terminées" icon="check-circle-2" variant="success" />
        <x-ui.stat-card :value="$ongoingCount" label="En cours" icon="loader" variant="accent" />
        <x-ui.stat-card :value="$nonStartedCount" label="Non démarrées" icon="clock" variant="warning" />
        <x-ui.stat-card :value="$lateActivitiesCount" label="En retard" icon="alert-triangle" variant="error" />
    </div>

    {{-- Filters --}}
    <x-ui.card class="mb-6" :noPadding="false">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-ui.input 
                wire:model.live.debounce.300ms="search" 
                placeholder="Rechercher une activité..." 
                icon="search" />

            <x-ui.select wire:model.live="responsibleUserFilter" icon="user">
                <option value="">Tous les responsables</option>
                @foreach ($availableUsers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </x-ui.select>

            <x-ui.select wire:model.live="statusFilter" icon="filter">
                <option value="">Tous les statuts</option>
                <option value="En cours">En cours</option>
                <option value="Terminé">Terminé</option>
                <option value="En retard">En retard</option>
            </x-ui.select>

            <x-ui.select wire:model.live="perPage" icon="list">
                @if ($totalActivitiesCount >= 10)
                    <option value="10">10 par page</option>
                @endif
                @if ($totalActivitiesCount >= 25)
                    <option value="25">25 par page</option>
                @endif
                @if ($totalActivitiesCount >= 50)
                    <option value="50">50 par page</option>
                @endif
                <option value="{{ $totalActivitiesCount }}">Tout afficher</option>
            </x-ui.select>
        </div>
    </x-ui.card>

    {{-- Activities Table --}}
    <x-ui.section title="Détails des activités" icon="clipboard-list">
        <div class="overflow-x-auto -mx-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Description</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Responsable</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Statut</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Progression</th>
                        <th class="px-6 py-3 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                    @forelse ($activities as $activity)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $activity->description }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $activity->responsibleUser->name ?? 'Non assigné' }}</td>
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
                                    <div class="w-16 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                        <div class="h-full bg-accent rounded-full transition-all" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-500">{{ $progress }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="openActivityDetails('{{ $activity->id }}')" class="navbar-action" title="Voir">
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
                                <x-ui.empty-state icon="clipboard-x" title="Aucune activité trouvée" description="Modifiez vos filtres ou ajoutez des activités au projet." />
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
        @livewire('project.project-dashboard-activity-show-livewire', ['activityId' => $selectedActivityId])
    @endif

</x-ui.page-layout>
