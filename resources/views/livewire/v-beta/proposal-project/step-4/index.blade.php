<div class="space-y-6">
    <div class="flex items-center justify-between px-1">
        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em]">Résultats Attendus</label>
        <button type="button" wire:click="addExpectedResult" class="text-[10px] font-black text-accent hover:opacity-80 uppercase flex items-center gap-1.5 transition-opacity tracking-widest">
            <i class="fas fa-plus-circle"></i> Ajouter
        </button>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @foreach($expectedResults as $index => $result)
            <div wire:key="expected-result-{{ $index }}-{{ $result['id'] ?? $loop->index }}" 
                 class="relative p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-3xl group animate-fadeIn transition-colors hover:border-slate-300 dark:hover:border-slate-700">
                <button type="button" wire:click="removeExpectedResult({{ $index }})" 
                        class="absolute top-6 right-6 text-slate-300 hover:text-rose-500 transition-colors bg-white dark:bg-slate-900 rounded-full w-8 h-8 flex items-center justify-center border border-transparent shadow-sm hover:border-rose-100 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                    <i class="fas fa-trash-alt text-[10px]"></i>
                </button>

                <div class="flex gap-5">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center font-black text-slate-500 text-[10px] shadow-inner border border-slate-100 dark:border-slate-700">R{{ $index + 1 }}</div>
                    
                    <div class="flex-1 space-y-3 pt-0.5 pr-8">
                        <input type="hidden" wire:model="expectedResults.{{ $index }}.id">
                        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">Description du Résultat <span class="text-rose-500">*</span></label>
                        <div wire:ignore class="rounded-xl overflow-hidden border shadow-sm @error('expectedResults.' . $index . '.description') border-rose-500 ring-2 ring-rose-500/20 @else border-slate-200 dark:border-slate-700 @enderror">
                            <textarea id="expected-result-description-{{ $index }}" class="summernote" data-field="expectedResults.{{ $index }}.description">{!! $result['description'] ?? '' !!}</textarea>
                        </div>
                        @error('expectedResults.' . $index . '.description') <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>