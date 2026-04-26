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
                @error('name') <span class="text-xs text-error mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Champ Description --}}
            <div>
                <label class="block text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">Description</label>
                <textarea 
                    wire:model="description" 
                    rows="3" 
                    placeholder="Décrivez cette catégorie..."
                    class="w-full rounded-xl border border-border bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/30 focus:border-accent transition-all placeholder:text-muted resize-none"
                ></textarea>
                @error('description') <span class="text-xs text-error mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-border-light dark:border-surface-alt">
            <x-ui.button type="button" variant="ghost" wire:click="$parent.closeModal">
                Annuler
            </x-ui.button>
            <x-ui.button type="submit" variant="accent" icon="check" loadingText="Enregistrement..." loadingTarget="saveCategory">
                {{ $editing ? 'Mettre à jour' : 'Créer la catégorie' }}
            </x-ui.button>
        </div>
    </form>
</div>
