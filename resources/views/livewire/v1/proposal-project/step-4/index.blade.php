<div class="space-y-6">
    <div class="flex items-center justify-between px-1">
        <label class="block text-[11px] font-black text-subtle uppercase tracking-[0.15em]">Resultats Attendus</label>
        <x-ui.button type="button" variant="ghost" size="sm" icon="plus-circle" wire:click="addExpectedResult">
            Ajouter
        </x-ui.button>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @foreach($expectedResults as $index => $result)
            <x-ui.card wire:key="expected-result-{{ $index }}-{{ $result['id'] ?? $loop->index }}" class="relative overflow-visible" :noPadding="false">
                <button type="button" wire:click="removeExpectedResult({{ $index }})"
                        class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-card shadow-md border border-border-light flex items-center justify-center text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all z-10">
                    <x-lucide-trash-2 class="w-4 h-4" />
                </button>

                <div class="flex gap-5">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-surface flex items-center justify-center font-black text-accent text-[10px] shadow-inner border border-border-light">R{{ $index + 1 }}</div>

                    <div class="flex-1 space-y-3">
                        <input type="hidden" wire:model="expectedResults.{{ $index }}.id">

                        <div>
                            <div class="flex items-center mb-1">
                                <label class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">
                                    Description du Resultat <span class="text-rose-500">*</span>
                                </label>
                                <x-ui.ai-field-button field="result" :soIndex="$index" />
                            </div>
                            <textarea
                                wire:model.blur="expectedResults.{{ $index }}.description"
                                placeholder="Decrivez le resultat attendu..."
                                rows="3"
                                class="block w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 rounded-xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent sm:text-sm py-3 transition-all pl-4 pr-4"
                            ></textarea>
                            @error('expectedResults.' . $index . '.description')
                                <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p>
                            @enderror
                            <x-ui.ai-suggestion field="result.{{ $index }}" :activeField="$aiActiveField" :suggestion="$aiSuggestion" :loading="$aiLoading" />
                        </div>
                    </div>
                </div>
            </x-ui.card>
        @endforeach
    </div>
</div>
