<div>
    <form wire:submit.prevent="saveCategory">
        <div class="space-y-5">
            {{-- Champ Nom --}}
            <div>
                <x-ui.input 
                    wire:model="name" 
                    label="Nom de la catégorie" 
                    placeholder="Ex: Infrastructure, Éducation..."
                    icon="tag"
                    required
                />
                @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Champ Description --}}
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Description</label>
                <textarea 
                    wire:model="description" 
                    rows="3" 
                    placeholder="Décrivez cette catégorie..."
                    class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-700 dark:text-slate-200 px-4 py-3 focus:ring-2 focus:ring-accent/30 focus:border-accent transition-all placeholder:text-slate-400 resize-none"
                ></textarea>
                @error('description') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
            <x-ui.button type="button" variant="ghost" wire:click="$parent.closeModal">
                Annuler
            </x-ui.button>
            <x-ui.button type="submit" variant="accent" icon="check" loadingText="Enregistrement..." loadingTarget="saveCategory">
                {{ $editing ? 'Mettre à jour' : 'Créer la catégorie' }}
            </x-ui.button>
        </div>
    </form>
</div>
