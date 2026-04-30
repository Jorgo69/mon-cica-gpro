<div>
    <form wire:submit.prevent="saveCategory">
        <div class="space-y-5">
            {{-- Type --}}
            <div>
                <x-ui.select wire:model="type" label="Type de categorie" icon="filter" required>
                    <option value="">Selectionner un type</option>
                    @foreach($categoryTypes as $catType)
                        <option value="{{ $catType->value }}">{{ $catType->label() }}</option>
                    @endforeach
                </x-ui.select>
                @error('type') <span class="text-xs text-error mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Nom --}}
            <div>
                <x-ui.input
                    wire:model="name"
                    label="Nom de la categorie"
                    placeholder="Ex: Personnel, Infrastructure..."
                    icon="tag"
                    required
                />
                @error('name') <span class="text-xs text-error mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">Description</label>
                <textarea
                    wire:model="description"
                    rows="3"
                    placeholder="Decrivez cette categorie..."
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
            <x-ui.button type="submit" variant="accent" icon="check">
                {{ $editing ? 'Mettre a jour' : 'Creer la categorie' }}
            </x-ui.button>
        </div>
    </form>
</div>
