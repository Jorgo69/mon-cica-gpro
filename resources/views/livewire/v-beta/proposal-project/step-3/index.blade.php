<div class="space-y-8">
    {{-- BUT GENERAL --}}
    <div class="space-y-4">
        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">But Général du Projet</label>
        <div class="space-y-5">
            <div class="space-y-2">
                <label for="general_objective" class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">Objectif Général <span class="text-rose-500">*</span></label>
                <textarea id="general_objective" wire:model.defer="initialLogicalFramework.general_objective" rows="3"
                          class="block w-full px-4 py-3.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all text-sm shadow-sm @error('initialLogicalFramework.general_objective') border-rose-500 ring-2 ring-rose-500/20 @enderror"
                          placeholder="Ex: Contribuer à l'amélioration de la santé..."></textarea>
                @error('initialLogicalFramework.general_objective') <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="general_obj_indicators" class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">Indicateurs de réussite</label>
                    <textarea id="general_obj_indicators" wire:model.defer="initialLogicalFramework.general_obj_indicators" rows="2"
                              class="block w-full px-4 py-3.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 text-sm italic shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all"></textarea>
                </div>
                <div class="space-y-2">
                    <label for="general_obj_verification_sources" class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">Sources de Vérification</label>
                    <textarea id="general_obj_verification_sources" wire:model.defer="initialLogicalFramework.general_obj_verification_sources" rows="2"
                               class="block w-full px-4 py-3.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 text-sm italic shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all"
                               placeholder="Ex: Rapports d'activités, registres..."></textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- OBJECTIFS SPECIFIQUES --}}
    <div class="pt-8 border-t border-slate-100 dark:border-slate-800 space-y-6">
        <div class="flex items-center justify-between px-1">
            <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em]">Objectifs Spécifiques</label>
            <button type="button" wire:click="addSpecificObjective" class="text-[10px] font-black text-accent hover:opacity-80 uppercase flex items-center gap-1.5 transition-opacity tracking-widest">
                <i class="fas fa-plus-circle"></i> Ajouter
            </button>
        </div>

        <div class="space-y-4">
            @foreach($specificObjectives as $index => $objective)
                <div wire:key="spec-obj-{{ $index }}-{{ $objective['id'] ?? $loop->index }}" 
                     class="relative p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-3xl group animate-fadeIn transition-colors hover:border-slate-300 dark:hover:border-slate-700">
                    <button type="button" wire:click="removeSpecificObjective({{ $index }})" 
                            class="absolute top-6 right-6 text-slate-300 hover:text-rose-500 transition-colors bg-white dark:bg-slate-900 rounded-full w-8 h-8 flex items-center justify-center border border-transparent shadow-sm hover:border-rose-100 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                        <i class="fas fa-trash-alt text-[10px]"></i>
                    </button>

                    <div class="flex gap-5">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center font-black text-slate-500 text-xs shadow-inner border border-slate-100 dark:border-slate-700">{{ $index + 1 }}</div>
                        <div class="flex-1 space-y-4 pt-1 pr-8">
                            <input type="hidden" wire:model="specificObjectives.{{ $index }}.id">
                            
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">Description <span class="text-rose-500">*</span></label>
                                <textarea wire:model.defer="specificObjectives.{{ $index }}.description" rows="2"
                                          class="block w-full px-4 py-3.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 text-sm shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all @error('specificObjectives.' . $index . '.description') border-rose-500 ring-2 ring-rose-500/20 @enderror"
                                          placeholder="Définissez l'objectif précis..."></textarea>
                                @error('specificObjectives.' . $index . '.description') <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Indicateurs</label>
                                    <textarea wire:model.defer="specificObjectives.{{ $index }}.indicators" rows="2"
                                              class="block w-full px-4 py-3 rounded-xl border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-xs italic shadow-inner text-slate-700 dark:text-slate-300 focus:ring-1 focus:ring-accent focus:border-accent transition-all"></textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Sources & Hypothèses</label>
                                    <div class="space-y-2 relative">
                                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                            <i class="fas fa-file-alt text-slate-300 text-[10px]"></i>
                                        </div>
                                        <input type="text" wire:model.defer="specificObjectives.{{ $index }}.verification_sources" placeholder="Sources de vérification"
                                               class="block w-full pl-8 pr-4 py-2.5 rounded-xl border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-[10px] italic shadow-inner text-slate-700 dark:text-slate-300 focus:ring-1 focus:ring-accent focus:border-accent transition-all">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                                <i class="fas fa-lightbulb text-slate-300 text-[10px]"></i>
                                            </div>
                                            <input type="text" wire:model.defer="specificObjectives.{{ $index }}.assumptions" placeholder="Hypothèses critiques"
                                                class="block w-full pl-8 pr-4 py-2.5 rounded-xl border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-[10px] italic shadow-inner text-slate-700 dark:text-slate-300 focus:ring-1 focus:ring-accent focus:border-accent transition-all">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>