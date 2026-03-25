<x-ui.page-layout>

    <x-ui.page-header title="Types de Projets" subtitle="Configurez les catégories et champs dynamiques de vos projets">
        <x-slot:actions>
            <x-ui.button tag="a" :href="route('admin.project.types.create')" variant="accent" icon="plus" size="lg">
                Créer un nouveau type
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($projectTypes as $type)
            <x-ui.card>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-1">{{ $type->name }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-4">{{ $type->description }}</p>
                <div class="flex items-center justify-between">
                    <x-ui.badge variant="accent" size="sm" icon="tag">{{ $type->category ?? 'N/A' }}</x-ui.badge>
                    <div class="flex items-center gap-1.5">
                        <x-ui.button tag="a" :href="route('admin.project.types.edit', ['projectTypeId' => $type->id])" variant="ghost" icon="pencil" size="sm" />
                        <x-ui.button 
                            wire:click="deleteProjectType('{{ $type->id }}')" 
                            onclick="confirm('Êtes-vous sûr de vouloir supprimer ce type de projet ?') || event.stopImmediatePropagation()"
                            variant="ghost" icon="trash-2" size="sm" class="text-error hover:bg-error/5" />
                    </div>
                </div>
            </x-ui.card>
        @empty
            <div class="col-span-full">
                <x-ui.empty-state icon="layout-grid" title="Aucun type de projet" description="Créez votre premier type de projet pour commencer." />
            </div>
        @endforelse
    </div>

</x-ui.page-layout>
