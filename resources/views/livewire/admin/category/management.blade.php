<x-ui.page-layout>

    <x-ui.page-header title="Gestion des Categories" subtitle="Organisez et parametrez les categories de votre organisation">
        <x-slot:actions>
            <x-ui.button wire:click="openModal" variant="accent" icon="plus" size="lg">
                Nouvelle Categorie
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Filtres --}}
    <x-ui.card class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-ui.input wire:model.live.debounce.300ms="search" placeholder="Rechercher une categorie..." icon="search" />
            <x-ui.select wire:model.live="typeFilter" icon="filter">
                <option value="">Tous les types</option>
                @foreach($categoryTypes as $type)
                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                @endforeach
            </x-ui.select>
        </div>
    </x-ui.card>

    {{-- Modal --}}
    @if($showModal)
        <x-ui.modal :show="true"
                    :title="$editingCategoryId ? 'Modifier la categorie' : 'Nouvelle categorie'"
                    id="category-management">
            @livewire('v-beta.admin.category.category-modal-form-livewire', [
                'editingCategoryId' => $editingCategoryId,
            ], key('category-form-' . ($editingCategoryId ?? 'new')))
        </x-ui.modal>
    @endif

    {{-- Table --}}
    <x-ui.section title="Categories" icon="tag" :noPadding="true">
        @if ($categories->isEmpty())
            <x-ui.empty-state icon="tag" title="Aucune categorie trouvee" description="Creez votre premiere categorie ou ajustez vos filtres." />
        @else
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th class="cursor-pointer" wire:click="sortBy('name')">
                        <div class="flex items-center gap-1">
                            Nom
                            @if ($sortField === 'name')
                                <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                            @endif
                        </div>
                    </x-ui.table.th>
                    <x-ui.table.th class="cursor-pointer" wire:click="sortBy('type')">
                        <div class="flex items-center gap-1">
                            Type
                            @if ($sortField === 'type')
                                <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                            @endif
                        </div>
                    </x-ui.table.th>
                    <x-ui.table.th>Description</x-ui.table.th>
                    <x-ui.table.th>Source</x-ui.table.th>
                    <x-ui.table.th align="right">Actions</x-ui.table.th>
                </x-slot:headers>

                @foreach ($categories as $category)
                    <x-ui.table.row>
                        <x-ui.table.td class="font-semibold text-heading">
                            {{ $category->name }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            @php $typeEnum = $category->type instanceof \App\Enums\AdminCategoryType ? $category->type : \App\Enums\AdminCategoryType::tryFrom($category->type); @endphp
                            @if($typeEnum)
                                <x-ui.badge :variant="$typeEnum->color()" size="sm">{{ $typeEnum->label() }}</x-ui.badge>
                            @else
                                <span class="text-muted text-xs">{{ $category->type }}</span>
                            @endif
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <span class="text-subtle text-xs">{{ \Illuminate\Support\Str::limit($category->description, 60) }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            @if($category->is_system)
                                <x-ui.badge variant="slate" size="sm">Systeme</x-ui.badge>
                            @else
                                <x-ui.badge variant="accent" size="sm">Organisation</x-ui.badge>
                            @endif
                        </x-ui.table.td>
                        <x-ui.table.td align="right">
                            @if($category->is_system)
                                <span class="text-[10px] text-muted italic">Protege</span>
                            @else
                                <div class="flex items-center justify-end gap-1">
                                    <x-ui.button wire:click="openModal('{{ $category->id }}')" variant="ghost" icon="pencil" size="sm" />
                                    <x-ui.button wire:click="deleteCategory('{{ $category->id }}')" wire:confirm="Supprimer cette categorie ?" variant="ghost" icon="trash-2" size="sm" class="text-error hover:text-error" />
                                </div>
                            @endif
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforeach
            </x-ui.table>
        @endif

        @if($categories->isNotEmpty())
            <div class="px-6 py-4 border-t border-border-light dark:border-surface-alt">
                {{ $categories->links() }}
            </div>
        @endif
    </x-ui.section>

</x-ui.page-layout>
