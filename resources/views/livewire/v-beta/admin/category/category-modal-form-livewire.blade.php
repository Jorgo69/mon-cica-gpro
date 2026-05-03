<div>
    <form wire:submit.prevent="saveCategory">
        <div class="space-y-5">
            {{-- Type --}}
            <div>
                <x-ui.select wire:model="type" :label="__('admin.categories.type_label')" icon="filter" required>
                    <option value="">{{ __('admin.categories.select_type') }}</option>
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
                    :label="__('admin.categories.name_label')"
                    :placeholder="__('admin.categories.name_placeholder')"
                    icon="tag"
                    required
                />
                @error('name') <span class="text-xs text-error mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">{{ __('admin.categories.description') }}</label>
                <textarea
                    wire:model="description"
                    rows="3"
                    placeholder="{{ __('admin.categories.desc_placeholder') }}"
                    class="w-full rounded-xl border border-border bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/30 focus:border-accent transition-all placeholder:text-muted resize-none"
                ></textarea>
                @error('description') <span class="text-xs text-error mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-border-light dark:border-surface-alt">
            <x-ui.button type="button" variant="ghost" wire:click="$parent.closeModal">
                {{ __('common.cancel') }}
            </x-ui.button>
            <x-ui.button type="submit" variant="accent" icon="check">
                {{ $editing ? __('admin.categories.update') : __('admin.categories.create') }}
            </x-ui.button>
        </div>
    </form>
</div>
