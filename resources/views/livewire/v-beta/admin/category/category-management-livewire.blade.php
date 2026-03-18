<x-ui.page-layout>

    <x-ui.page-header title="Gestion des Catégories" subtitle="Organisez vos types de projets par catégorie">
        <x-slot:actions>
            <x-ui.button wire:click="openModal" variant="accent" icon="plus" loadingText="Chargement...">
                Nouvelle Catégorie
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Search --}}
    <div class="mb-6">
        <x-ui.input 
            wire:model.live.debounce.300ms="search" 
            placeholder="Rechercher une catégorie..." 
            icon="search" 
        />
    </div>

    {{-- Modal : Livewire contrôle la présence dans le DOM via @if --}}
    @if($showModal)
        <x-ui.modal :show="true" 
                    :title="$editingCategoryId ? 'Modifier la catégorie' : 'Nouvelle catégorie'"
                    id="category-management">
            
            @livewire('v-beta.admin.category.category-modal-form-livewire', [
                'editingCategoryId' => $editingCategoryId,
            ], key('category-form-' . ($editingCategoryId ?? 'new')))

        </x-ui.modal>
    @endif

    {{-- Section Table --}}
    <x-ui.section title="Liste des Catégories" icon="tag" :noPadding="true">
        @if ($categories->isEmpty())
            <x-ui.empty-state 
                icon="tag" 
                title="Aucune catégorie trouvée" 
                description="Créez votre première catégorie pour organiser vos projets ou ajustez votre recherche." 
            />
        @else
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th class="cursor-pointer group" wire:click="sortBy('name')">
                        <div class="flex items-center gap-1">
                            Nom de la catégorie
                            @if ($sortField === 'name')
                                <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                            @endif
                        </div>
                    </x-ui.table.th>
                    
                    <x-ui.table.th class="cursor-pointer group" wire:click="sortBy('description')">
                        <div class="flex items-center gap-1">
                            Description
                            @if ($sortField === 'description')
                                <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                            @endif
                        </div>
                    </x-ui.table.th>

                    <x-ui.table.th align="right">Actions</x-ui.table.th>
                </x-slot:headers>

                @foreach ($categories as $category)
                    <x-ui.table.row>
                        <x-ui.table.td class="font-semibold text-slate-800 dark:text-slate-100">
                            {{ $category->name }}
                        </x-ui.table.td>
                        
                        <x-ui.table.td>
                            {{ \Illuminate\Support\Str::limit($category->description, 80) }}
                        </x-ui.table.td>
                        
                        <x-ui.table.td align="right">
                            <x-ui.button wire:click="openModal('{{ $category->id }}')" variant="ghost" icon="pencil" size="sm" />
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforeach
            </x-ui.table>
        @endif

        @if($categories->isNotEmpty())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                {{ $categories->links() }}
            </div>
        @endif
    </x-ui.section>

</x-ui.page-layout>
