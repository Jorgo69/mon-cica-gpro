<x-ui.page-layout>
    @if ($activity)
        {{-- Progress Bar --}}
        @include('livewire.v-beta.activity.include.progres-bar')

        {{-- Page Header --}}
        <x-ui.page-header :title="$activity->description">
            <x-slot:actions>
                <x-ui.button tag="a" :href="route('activity.index')" variant="outline" icon="arrow-left" size="sm">
                    Retour
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>
        @if($activity->project)
            <p class="text-sm text-slate-500 dark:text-slate-400 -mt-6 mb-6">
                Projet : <a href="#" class="font-semibold text-accent hover:underline">{{ $activity->project->title }}</a>
            </p>
        @endif

        <div class="space-y-6">

            {{-- Section 1: Informations Générales --}}
            <x-ui.section title="Informations Générales" icon="info">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28 shrink-0 pt-0.5">Statut</span>
                            @php $actStatus = $activity->status; @endphp
                            <x-ui.badge :variant="$actStatus?->color() ?? 'slate'">
                                {{ $actStatus instanceof \App\Enums\ActivityStatus ? $actStatus->label() : ($actStatus instanceof \BackedEnum ? $actStatus->value : (string)$actStatus) }}
                            </x-ui.badge>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Début</span>
                            <span class="text-slate-600 dark:text-slate-300">{{ $activity->start_date }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Fin</span>
                            <span class="text-slate-600 dark:text-slate-300">{{ $activity->end_date }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Responsable</span>
                            <span class="text-slate-600 dark:text-slate-300">{{ $activity->responsibleUser->name ?? 'Non assigné' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Budget</span>
                            <span class="text-slate-600 dark:text-slate-300">{{ $activity->budget ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Créé</span>
                            <span class="text-slate-600 dark:text-slate-300">{{ $activity->created_at }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Mis à jour</span>
                            <span class="text-slate-600 dark:text-slate-300">{{ $activity->updated_at }}</span>
                        </div>
                        @if($activity->result)
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Contribue au résultat</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl">{{ $activity->result->description }}</p>
                        </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Créateur projet</span>
                            <span class="text-slate-600 dark:text-slate-300">{{ $activity->result->specificObjective->logicalFramework->project->creator->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </x-ui.section>

            {{-- Section 2: Contexte du projet --}}
            @if($activity->project)
                <x-ui.section title="Contexte du Projet" icon="briefcase">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Titre</span>
                                <span class="text-slate-600 dark:text-slate-300">{{ $activity->project->title }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Code</span>
                                <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-lg">{{ $activity->project->project_code }}</span>
                            </div>
                            @if($activity->project->projectType)
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Type</span>
                                <x-ui.badge variant="accent" size="sm">{{ $activity->project->projectType->name }}</x-ui.badge>
                            </div>
                            @endif
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Période</span>
                                <span class="text-slate-600 dark:text-slate-300">{{ $activity->project->start_date }} → {{ $activity->project->end_date }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Statut</span>
                                @php
                                    $projStatusEnum = $activity->project->status instanceof \App\Enums\ProjectStatus ? $activity->project->status : \App\Enums\ProjectStatus::tryFrom($activity->project->status);
                                    $projBadgeVariant = $projStatusEnum ? $projStatusEnum->color() : 'slate';
                                @endphp
                                <x-ui.badge :variant="$projBadgeVariant">{{ $projStatusEnum ? $projStatusEnum->label() : $activity->project->status }}</x-ui.badge>
                            </div>
                        </div>
                    </div>
                </x-ui.section>
            @endif

            {{-- Section 3: Sous-activités --}}
            <x-ui.section title="Gestion des Sous-Activités" icon="git-branch">
                @can('create', $activity)
                    <x-slot:headerActions>
                        <x-ui.button wire:click="openModalForSubActivity" variant="accent" icon="plus" size="sm">
                            Ajouter
                        </x-ui.button>
                    </x-slot:headerActions>
                @endcan

                {{-- Sub-Activity Modal --}}
                <x-ui.modal :show="$showModalForSubActivity" title="Sous-Activité" wire:close="closeModalForSubActivity">
                    @livewire('v-beta.sub-activity.sub-activity-form-livewire', [
                        'activityId' => $activity->id,
                        'subActivityToEditId' => $editingSubActivityId,
                    ])
                </x-ui.modal>

                @include('messages.index', ['context' => 'sub-activity'])
                @include('livewire.v-beta.sub-activity.include.sub-activity')
            </x-ui.section>

            {{-- Section 4: Ressources --}}
            <x-ui.section title="Gestion des Ressources" icon="package">
                @can('create', $activity)
                    <x-slot:headerActions>
                        <x-ui.button wire:click="openModal" variant="accent" icon="plus" size="sm">
                            Ajouter
                        </x-ui.button>
                    </x-slot:headerActions>
                @endcan

                {{-- Resource Modal --}}
                <x-ui.modal :show="$showModal" title="Ressource" wire:close="closeModal">
                    @livewire('v-beta.resource.resource-form-livewire', [
                        'activityId' => $activity->id,
                        'resourceToEditId' => $editingResourceId,
                    ])
                </x-ui.modal>

                @include('messages.index', ['context' => 'resource'])
                @include('livewire.v-beta.resource.include.resource')
            </x-ui.section>

        </div>
    @else
        <x-ui.empty-state icon="activity" title="Activité non trouvée" description="L'activité demandée n'existe pas ou a été supprimée." />
    @endif
</x-ui.page-layout>
