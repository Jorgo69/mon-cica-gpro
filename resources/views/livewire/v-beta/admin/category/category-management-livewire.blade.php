<x-ui.page-layout>

    <x-ui.page-header title="Gestion des Catégories" subtitle="Organisez vos types de projets par catégorie">
        <x-slot:actions>
            <x-ui.button wire:click="openModal" variant="accent" icon="plus" size="md">
                Nouvelle Catégorie
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Search --}}
    <div class="mb-6">
        <x-ui.input wire:model.live.debounce.300ms="search" placeholder="Rechercher une catégorie..." icon="search" />
    </div>

    {{-- Modal --}}
    <x-ui.modal wire:show="showModal" 
                 :title="$editingCategoryId ? 'Modifier la catégorie' : 'Nouvelle catégorie'"
                 id="category-management">
        @if($showModal)
            @livewire('v-beta.admin.category.category-modal-form-livewire', [
                'editingCategoryId' => $editingCategoryId,
            ], key('category-form-' . ($editingCategoryId ?? 'new')))
        @endif
    </x-ui.modal>

    {{-- Table --}}
    <x-ui.section title="Catégories" icon="tag" :noPadding="false">
        <div class="overflow-x-auto -mx-6">
            @if ($categories->isEmpty())
                <x-ui.empty-state icon="tag" title="Aucune catégorie trouvée" description="Créez votre première catégorie pour organiser vos projets." />
            @else
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800">
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer group" wire:click="sortBy('name')">
                                <div class="flex items-center gap-1">
                                    Nom
                                    @if ($sortField === 'name')
                                        <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer group" wire:click="sortBy('description')">
                                <div class="flex items-center gap-1">
                                    Description
                                    @if ($sortField === 'description')
                                        <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @foreach ($categories as $category)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $category->name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $category->description }}</td>
                                <td class="px-6 py-4 text-right">
                                    <x-ui.button wire:click="openModal('{{ $category->id }}')" variant="ghost" icon="pencil" size="sm" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if($categories->isNotEmpty())
            <x-slot:footer>
                {{ $categories->links() }}
            </x-slot:footer>
        @endif
    </x-ui.section>

</x-ui.page-layout>
