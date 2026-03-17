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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-ui.input 
                    label="Indicateurs de réussite" 
                    wire:model.defer="initialLogicalFramework.general_obj_indicators" 
                    placeholder="Indicateurs clés..." 
                    icon="bar-chart-3"
                />
                <x-ui.input 
                    label="Sources de Vérification" 
                    wire:model.defer="initialLogicalFramework.general_obj_verification_sources" 
                    placeholder="Rapports, registres..." 
                    icon="file-text"
                />
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

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-ui.input 
                                    label="Indicateurs" 
                                    wire:model.defer="specificObjectives.{{ $index }}.indicators" 
                                    placeholder="Mesuré par..." 
                                    icon="list-checks"
                                />
                                <div class="grid grid-cols-1 gap-4">
                                    <x-ui.input 
                                        label="Sources" 
                                        wire:model.defer="specificObjectives.{{ $index }}.verification_sources" 
                                        placeholder="Preuves..." 
                                        icon="file-search"
                                    />
                                    <x-ui.input 
                                        label="Hypothèses" 
                                        wire:model.defer="specificObjectives.{{ $index }}.assumptions" 
                                        placeholder="Risques/Hypothèses..." 
                                        icon="help-circle"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    </div>
</div>