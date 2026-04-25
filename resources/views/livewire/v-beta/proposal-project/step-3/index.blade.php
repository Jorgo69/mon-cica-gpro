<div class="space-y-8">
    {{-- BUT GENERAL --}}
    <x-ui.card :noPadding="false">
        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1 mb-4">But Général du Projet</label>
        <div class="space-y-6">
            <x-ui.input
                label="Objectif Général"
                wire:model.defer="initialLogicalFramework.general_objective"
                placeholder="Ex: Contribuer à l'amélioration de la santé..."
                icon="target"
                :error="$errors->first('initialLogicalFramework.general_objective')"
                required
            />

            {{-- INDICATEURS REPETABLES - Niveau Cadre Logique --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">Indicateurs</label>
                    <button type="button" wire:click="addIndicator('logframe')"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-accent hover:text-accent/80 transition-colors">
                        <x-lucide-plus-circle class="w-4 h-4" />
                        Ajouter un indicateur
                    </button>
                </div>

                @forelse($initialLogicalFramework['indicators_list'] ?? [] as $iIdx => $indicator)
                    <div wire:key="lf-indicator-{{ $iIdx }}" class="relative bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-100 dark:border-slate-700">
                        <button type="button" wire:click="removeIndicator('logframe', null, {{ $iIdx }})"
                            class="absolute top-2 right-2 text-rose-400 hover:text-rose-600 transition-colors">
                            <x-lucide-x class="w-4 h-4" />
                        </button>

                        <div class="space-y-3 pr-6">
                            <x-ui.input
                                label="Description de l'indicateur"
                                wire:model.defer="initialLogicalFramework.indicators_list.{{ $iIdx }}.description"
                                placeholder="Ex: Taux de couverture vaccinale..."
                                icon="bar-chart-3"
                                :error="$errors->first('initialLogicalFramework.indicators_list.' . $iIdx . '.description')"
                            />
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <x-ui.input
                                    label="Source de vérification"
                                    wire:model.defer="initialLogicalFramework.indicators_list.{{ $iIdx }}.verification_source"
                                    placeholder="Rapports, registres..."
                                    icon="file-text"
                                />
                                <x-ui.input
                                    label="Hypothèse"
                                    wire:model.defer="initialLogicalFramework.indicators_list.{{ $iIdx }}.assumption"
                                    placeholder="Conditions nécessaires..."
                                    icon="help-circle"
                                />
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400 dark:text-slate-500 text-sm">
                        <x-lucide-bar-chart-3 class="w-8 h-8 mx-auto mb-2 opacity-40" />
                        <p>Aucun indicateur ajouté.</p>
                        <p class="text-xs mt-1">Cliquez sur "Ajouter un indicateur" pour commencer.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </x-ui.card>

    {{-- OBJECTIFS SPECIFIQUES --}}
    <div class="pt-8 border-t border-slate-100 dark:border-slate-800 space-y-6">
        <div class="flex items-center justify-between px-1">
            <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em]">Objectifs Spécifiques</label>
            <x-ui.button type="button" variant="ghost" size="sm" icon="plus-circle" wire:click="addSpecificObjective">
                Ajouter
                </x-ui.button>
        </div>

        <div class="space-y-4">
            @foreach($specificObjectives as $index => $objective)
                <x-ui.card wire:key="spec-obj-{{ $index }}-{{ $objective['id'] ?? $loop->index }}" class="relative overflow-visible" :noPadding="false">
                    <button type="button" wire:click="removeSpecificObjective({{ $index }})"
                            class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-white dark:bg-slate-800 shadow-md border border-slate-100 dark:border-slate-700 flex items-center justify-center text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all z-10">
                        <x-lucide-trash-2 class="w-4 h-4" />
                    </button>

                    <div class="flex gap-5">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center font-black text-accent text-xs shadow-inner border border-slate-100 dark:border-slate-700">{{ $index + 1 }}</div>
                        <div class="flex-1 space-y-6">
                            <input type="hidden" wire:model="specificObjectives.{{ $index }}.id">

                            <x-ui.input
                                label="Description"
                                wire:model.defer="specificObjectives.{{ $index }}.description"
                                placeholder="Définissez l'objectif précis..."
                                icon="crosshair"
                                :error="$errors->first('specificObjectives.' . $index . '.description')"
                                required
                            />

                            {{-- INDICATEURS REPETABLES - Niveau Objectif Spécifique --}}
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">Indicateurs</label>
                                    <button type="button" wire:click="addIndicator('objective', {{ $index }})"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-accent hover:text-accent/80 transition-colors">
                                        <x-lucide-plus-circle class="w-4 h-4" />
                                        Ajouter
                                    </button>
                                </div>

                                @forelse($objective['indicators_list'] ?? [] as $iIdx => $indicator)
                                    <div wire:key="obj-{{ $index }}-indicator-{{ $iIdx }}" class="relative bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-100 dark:border-slate-700">
                                        <button type="button" wire:click="removeIndicator('objective', {{ $index }}, {{ $iIdx }})"
                                            class="absolute top-2 right-2 text-rose-400 hover:text-rose-600 transition-colors">
                                            <x-lucide-x class="w-4 h-4" />
                                        </button>

                                        <div class="space-y-3 pr-6">
                                            <x-ui.input
                                                label="Description de l'indicateur"
                                                wire:model.defer="specificObjectives.{{ $index }}.indicators_list.{{ $iIdx }}.description"
                                                placeholder="Ex: Nombre de bénéficiaires formés..."
                                                icon="bar-chart-3"
                                                :error="$errors->first('specificObjectives.' . $index . '.indicators_list.' . $iIdx . '.description')"
                                            />
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <x-ui.input
                                                    label="Source de vérification"
                                                    wire:model.defer="specificObjectives.{{ $index }}.indicators_list.{{ $iIdx }}.verification_source"
                                                    placeholder="Rapports, registres..."
                                                    icon="file-text"
                                                />
                                                <x-ui.input
                                                    label="Hypothèse"
                                                    wire:model.defer="specificObjectives.{{ $index }}.indicators_list.{{ $iIdx }}.assumption"
                                                    placeholder="Conditions nécessaires..."
                                                    icon="help-circle"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center py-3 text-slate-400 dark:text-slate-500 text-xs">Aucun indicateur. Cliquez sur "Ajouter" ci-dessus.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    </div>
</div>
