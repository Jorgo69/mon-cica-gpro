<div class="space-y-6">
    <div class="flex items-center justify-between px-1">
        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em]">Résultats Attendus</label>
        <x-ui.button type="button" variant="ghost" size="sm" icon="plus-circle" wire:click="addExpectedResult">
            Ajouter
        </x-ui.button>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @foreach($expectedResults as $index => $result)
            <x-ui.card wire:key="expected-result-{{ $index }}-{{ $result['id'] ?? $loop->index }}" class="relative overflow-visible" :noPadding="false">
                <button type="button" wire:click="removeExpectedResult({{ $index }})"
                        class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-white dark:bg-slate-800 shadow-md border border-slate-100 dark:border-slate-700 flex items-center justify-center text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all z-10">
                    <x-lucide-trash-2 class="w-4 h-4" />
                </button>

                <div class="flex gap-5">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center font-black text-accent text-[10px] shadow-inner border border-slate-100 dark:border-slate-700">R{{ $index + 1 }}</div>

                    <div class="flex-1 space-y-3">
                        <input type="hidden" wire:model="expectedResults.{{ $index }}.id">

                        <x-ui.rich-editor
                            name="expectedResults.{{ $index }}.description"
                            :value="$result['description'] ?? ''"
                            label="Description du Résultat"
                            :required="true"
                            placeholder="Décrivez le résultat attendu..."
                            :height="150"
                        />
                    </div>
                </div>
            </x-ui.card>
        @endforeach
    </div>
</div>
