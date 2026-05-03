<x-ui.page-layout>

    <x-ui.page-header :title="__('admin.types.list_title')" :subtitle="__('admin.types.list_subtitle')">
        <x-slot:actions>
            <x-ui.button tag="a" :href="route('admin.project.types.create')" variant="accent" icon="plus" size="lg">
                {{ __('admin.types.create_new') }}
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($projectTypes as $type)
            <x-ui.card class="{{ !$type->is_active ? 'opacity-50' : '' }}">
                {{-- Header: name + badges --}}
                <div class="flex items-start justify-between mb-2">
                    <h3 class="text-base font-bold text-heading">{{ $type->name }}</h3>
                    <div class="flex items-center gap-1 shrink-0">
                        @if($type->is_system)
                            <x-ui.badge variant="info" size="xs">Systeme</x-ui.badge>
                        @elseif(!$type->organization_id)
                            <x-ui.badge variant="accent" size="xs">Global</x-ui.badge>
                        @else
                            <x-ui.badge variant="subtle" size="xs">Org</x-ui.badge>
                        @endif

                        @if(!$type->is_active)
                            <x-ui.badge variant="warning" size="xs">Masque</x-ui.badge>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                <p class="text-sm text-subtle line-clamp-2 mb-3">{{ $type->description ?: '-' }}</p>

                {{-- Dynamic fields count --}}
                @php $fieldsCount = $type->dynamicFields->count(); @endphp
                @if($fieldsCount > 0)
                    <p class="text-[10px] text-muted mb-3">
                        <x-lucide-list class="w-3 h-3 inline" />
                        {{ $fieldsCount }} {{ $fieldsCount > 1 ? 'champs dynamiques' : 'champ dynamique' }}
                    </p>
                @endif

                {{-- Footer: category + actions --}}
                <div class="flex items-center justify-between pt-2 border-t border-border-light">
                    <x-ui.badge variant="accent" size="sm" icon="tag">{{ $type->category ?? 'N/A' }}</x-ui.badge>

                    <div class="flex items-center gap-1.5">
                        {{-- Edit --}}
                        <x-ui.button tag="a" :href="route('admin.project.types.edit', ['projectTypeId' => $type->id])" variant="ghost" icon="pencil" size="sm" />

                        {{-- Toggle active (ROOT only for system types, anyone for their own) --}}
                        @if(\App\Services\OrgContext::isRoot() || !$type->is_system)
                            <button wire:click="toggleActive('{{ $type->id }}')"
                                class="p-1.5 rounded-lg transition-colors {{ $type->is_active ? 'text-green-500 hover:bg-green-50 dark:hover:bg-green-900/20' : 'text-muted hover:bg-surface-alt' }}"
                                title="{{ $type->is_active ? 'Masquer' : 'Activer' }}">
                                @if($type->is_active)
                                    <x-lucide-eye class="w-4 h-4" />
                                @else
                                    <x-lucide-eye-off class="w-4 h-4" />
                                @endif
                            </button>
                        @endif

                        {{-- Delete (NEVER for system, only for own org types) --}}
                        @if(!$type->is_system && ($type->organization_id === \App\Services\OrgContext::orgId() || \App\Services\OrgContext::isRoot()))
                            <x-ui.button
                                wire:click="deleteProjectType('{{ $type->id }}')"
                                wire:confirm="{{ __('admin.types.confirm_delete') }}"
                                variant="ghost" icon="trash-2" size="sm" class="text-error hover:bg-error/5" />
                        @endif
                    </div>
                </div>
            </x-ui.card>
        @empty
            <div class="col-span-full">
                <x-ui.empty-state icon="layout-grid" :title="__('admin.types.no_types')" :description="__('admin.types.no_types_desc')" />
            </div>
        @endforelse
    </div>

</x-ui.page-layout>
