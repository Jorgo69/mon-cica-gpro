<div class="space-y-8">
    {{-- AI Logframe Suggestion (full structure) --}}
    @if(\App\Services\AI\AiService::isConfigured())
        <div class="flex items-center gap-3">
            <button wire:click="aiSuggestLogframe" wire:loading.attr="disabled" type="button"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors">
                <x-lucide-sparkles class="w-3.5 h-3.5" />
                <span wire:loading.remove wire:target="aiSuggestLogframe">{{ __('ai.suggest_logframe') }}</span>
                <span wire:loading wire:target="aiSuggestLogframe">{{ __('ai.thinking') }}</span>
            </button>
            <span class="text-[9px] text-muted">{{ __('ai.logframe_hint') }}</span>
        </div>

        @if($aiLogframe)
            <div class="p-4 bg-purple-50 dark:bg-purple-900/10 border border-purple-200 dark:border-purple-800 rounded-xl">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <span class="text-[9px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest flex items-center gap-1">
                        <x-lucide-sparkles class="w-3 h-3" /> {{ __('ai.logframe_suggestion') }}
                    </span>
                    <div class="flex gap-2">
                        <button wire:click="aiApplyLogframe" type="button" class="text-[9px] font-bold text-white bg-purple-600 hover:bg-purple-700 px-2 py-0.5 rounded transition-colors">
                            {{ __('ai.apply') }}
                        </button>
                        <button wire:click="aiDismiss" type="button" class="text-[9px] text-purple-400 hover:text-purple-600">
                            <x-lucide-x class="w-3 h-3" />
                        </button>
                    </div>
                </div>
                @if(isset($aiLogframe['general_objective']))
                    <div class="mb-2 p-2 bg-purple-100/50 dark:bg-purple-900/20 rounded-lg">
                        <span class="text-[9px] font-bold text-purple-500 uppercase">{{ __('ai.general_objective') }}</span>
                        <p class="text-xs text-heading mt-0.5">{{ $aiLogframe['general_objective'] }}</p>
                    </div>
                @endif
                @foreach($aiLogframe['specific_objectives'] ?? [] as $i => $so)
                    <div class="mb-2 ml-3 pl-3 border-l-2 border-purple-200 dark:border-purple-700">
                        <span class="text-[9px] font-bold text-purple-400">OS {{ $i + 1 }}</span>
                        <p class="text-xs text-heading">{{ $so['description'] }}</p>
                        @foreach($so['results'] ?? [] as $j => $r)
                            <div class="ml-3 mt-1 pl-2 border-l border-purple-100 dark:border-purple-800">
                                <span class="text-[8px] font-bold text-muted">R{{ $i+1 }}.{{ $j+1 }}</span>
                                <p class="text-[11px] text-subtle">{{ $r['description'] }}</p>
                                @foreach($r['activities'] ?? [] as $a)
                                    <p class="text-[10px] text-muted ml-2">- {{ $a }}</p>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    {{-- BUT GENERAL --}}
    <x-ui.card :noPadding="false">
        <label class="block text-[11px] font-black text-subtle uppercase tracking-[0.15em] ml-1 mb-4">But General du Projet</label>
        <div class="space-y-6">
            <div>
                <div class="flex items-center mb-1">
                    <label class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">
                        Objectif General <span class="text-rose-500">*</span>
                    </label>
                    <x-ui.ai-field-button field="general_objective" />
                </div>
                <textarea
                    wire:model.blur="initialLogicalFramework.general_objective"
                    placeholder="Ex: Contribuer a l'amelioration de la sante..."
                    rows="3"
                    class="block w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 rounded-xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent sm:text-sm py-3 transition-all pl-4 pr-4"
                ></textarea>
                @error('initialLogicalFramework.general_objective')
                    <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p>
                @enderror
                <x-ui.ai-suggestion field="general_objective" :activeField="$aiActiveField" :suggestion="$aiSuggestion" :loading="$aiLoading" />
            </div>

            {{-- INDICATEURS REPETABLES - Niveau Cadre Logique --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-black text-subtle uppercase tracking-[0.15em] ml-1">Indicateurs</label>
                    <button type="button" wire:click="addIndicator('logframe')"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-accent hover:text-accent/80 transition-colors">
                        <x-lucide-plus-circle class="w-4 h-4" />
                        Ajouter un indicateur
                    </button>
                </div>

                @forelse($initialLogicalFramework['indicators_list'] ?? [] as $iIdx => $indicator)
                    <div wire:key="lf-indicator-{{ $iIdx }}" class="relative bg-surface rounded-xl p-4 border border-border-light">
                        <button type="button" wire:click="removeIndicator('logframe', null, {{ $iIdx }})"
                            class="absolute top-2 right-2 text-error/70 hover:text-error transition-colors">
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
                                    label="Source de verification"
                                    wire:model.defer="initialLogicalFramework.indicators_list.{{ $iIdx }}.verification_source"
                                    placeholder="Rapports, registres..."
                                    icon="file-text"
                                />
                                <x-ui.input
                                    label="Hypothese"
                                    wire:model.defer="initialLogicalFramework.indicators_list.{{ $iIdx }}.assumption"
                                    placeholder="Conditions necessaires..."
                                    icon="help-circle"
                                />
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-muted text-sm">
                        <x-lucide-bar-chart-3 class="w-8 h-8 mx-auto mb-2 opacity-40" />
                        <p>Aucun indicateur ajoute.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </x-ui.card>

    {{-- OBJECTIFS SPECIFIQUES --}}
    <div class="pt-8 border-t border-border-light space-y-6">
        <div class="flex items-center justify-between px-1">
            <label class="block text-[11px] font-black text-subtle uppercase tracking-[0.15em]">Objectifs Specifiques</label>
            <x-ui.button type="button" variant="ghost" size="sm" icon="plus-circle" wire:click="addSpecificObjective">
                Ajouter
            </x-ui.button>
        </div>

        <div class="space-y-4">
            @foreach($specificObjectives as $index => $objective)
                <x-ui.card wire:key="spec-obj-{{ $index }}-{{ $objective['id'] ?? $loop->index }}" class="relative overflow-visible" :noPadding="false">
                    <button type="button" wire:click="removeSpecificObjective({{ $index }})"
                            class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-card shadow-md border border-border-light flex items-center justify-center text-error hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all z-10">
                        <x-lucide-trash-2 class="w-4 h-4" />
                    </button>

                    <div class="flex gap-5">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-surface flex items-center justify-center font-black text-accent text-xs shadow-inner border border-border-light">{{ $index + 1 }}</div>
                        <div class="flex-1 space-y-6">
                            <input type="hidden" wire:model="specificObjectives.{{ $index }}.id">

                            <div>
                                <div class="flex items-center mb-1">
                                    <label class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">
                                        Description <span class="text-rose-500">*</span>
                                    </label>
                                    <x-ui.ai-field-button field="specific_objective" :soIndex="$index" />
                                </div>
                                <textarea
                                    wire:model.blur="specificObjectives.{{ $index }}.description"
                                    placeholder="Definissez l'objectif precis..."
                                    rows="2"
                                    class="block w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 rounded-xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent sm:text-sm py-3 transition-all pl-4 pr-4"
                                ></textarea>
                                @error('specificObjectives.' . $index . '.description')
                                    <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p>
                                @enderror
                                <x-ui.ai-suggestion field="specific_objective.{{ $index }}" :activeField="$aiActiveField" :suggestion="$aiSuggestion" :loading="$aiLoading" />
                            </div>

                            {{-- INDICATEURS --}}
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-[11px] font-black text-subtle uppercase tracking-[0.15em] ml-1">Indicateurs</label>
                                    <button type="button" wire:click="addIndicator('objective', {{ $index }})"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-accent hover:text-accent/80 transition-colors">
                                        <x-lucide-plus-circle class="w-4 h-4" />
                                        Ajouter
                                    </button>
                                </div>

                                @forelse($objective['indicators_list'] ?? [] as $iIdx => $indicator)
                                    <div wire:key="obj-{{ $index }}-indicator-{{ $iIdx }}" class="relative bg-surface rounded-xl p-4 border border-border-light">
                                        <button type="button" wire:click="removeIndicator('objective', {{ $index }}, {{ $iIdx }})"
                                            class="absolute top-2 right-2 text-error/70 hover:text-error transition-colors">
                                            <x-lucide-x class="w-4 h-4" />
                                        </button>
                                        <div class="space-y-3 pr-6">
                                            <x-ui.input
                                                label="Description de l'indicateur"
                                                wire:model.defer="specificObjectives.{{ $index }}.indicators_list.{{ $iIdx }}.description"
                                                placeholder="Ex: Nombre de beneficiaires formes..."
                                                icon="bar-chart-3"
                                            />
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <x-ui.input
                                                    label="Source de verification"
                                                    wire:model.defer="specificObjectives.{{ $index }}.indicators_list.{{ $iIdx }}.verification_source"
                                                    placeholder="Rapports, registres..."
                                                    icon="file-text"
                                                />
                                                <x-ui.input
                                                    label="Hypothese"
                                                    wire:model.defer="specificObjectives.{{ $index }}.indicators_list.{{ $iIdx }}.assumption"
                                                    placeholder="Conditions necessaires..."
                                                    icon="help-circle"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center py-3 text-muted text-xs">Aucun indicateur.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    </div>
</div>
