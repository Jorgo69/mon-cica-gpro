<x-ui.page-layout>

    {{-- Header Section --}}
    <x-ui.page-header :title="__('resources.title')" :subtitle="__('resources.subtitle')">
        <x-slot:actions>
            {{-- Note: Usually resources are added within an activity context, 
                 but if there's a global creator, we'd point here. 
                 For now, keeping consistency with user's existing structure. --}}
            <x-ui.button variant="outline" icon="refresh-cw" wire:click="$refresh">
                {{ __('common.refresh') }}
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Resources Table --}}
    <x-ui.section :title="__('resources.count_label')" icon="package" :noPadding="false">
        <div class="overflow-x-auto -mx-6">
            @if ($resources->isEmpty())
                <x-ui.empty-state icon="box" :title="__('resources.no_resources_listed')" :description="__('resources.no_resources_desc')" />
            @else
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border-light dark:border-surface-alt">
                            <th class="px-6 py-3 text-left">
                                <span class="text-[10px] font-black text-muted uppercase tracking-widest">{{ __('resources.designation') }}</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-[10px] font-black text-muted uppercase tracking-widest">{{ __('resources.type') }}</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-[10px] font-black text-muted uppercase tracking-widest">{{ __('resources.quantity') }}</span>
                            </th>
                            <th class="px-6 py-3 text-right">
                                <span class="text-[10px] font-black text-muted uppercase tracking-widest">{{ __('resources.unit_cost') }}</span>
                            </th>
                            <th class="px-6 py-3 text-right">
                                <span class="text-[10px] font-black text-muted uppercase tracking-widest">{{ __('resources.total') }}</span>
                            </th>
                            <th class="px-6 py-3 text-right">
                                <span class="text-[10px] font-black text-muted uppercase tracking-widest">{{ __('resources.actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light dark:divide-surface-alt/50">
                        @foreach ($resources as $resource)
                            <tr class="hover:bg-surface/50 dark:hover:bg-surface-alt/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <p class="text-sm font-bold text-heading truncate group-hover:text-accent transition-colors">{{ $resource->name }}</p>
                                        <p class="text-[10px] text-muted mt-0.5 truncate">{{ $resource->category ?: __('resources.uncategorized') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $typeVariant = match($resource->type) {
                                            'Humain' => 'primary',
                                            'Materiel' => 'accent',
                                            'Financier' => 'success',
                                            default => 'slate',
                                        };
                                    @endphp
                                    <x-ui.badge :variant="$typeVariant" size="sm">
                                        {{ $resource->type }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-subtle">{{ $resource->quantity }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-xs font-mono text-subtle">{{ number_format($resource->unit_cost, 2, ',', ' ') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-black text-heading">{{ number_format($resource->total_cost, 2, ',', ' ') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <x-ui.button variant="ghost" size="sm" icon="pencil" :title="__('common.edit')" />
                                        <x-ui.button variant="ghost" size="sm" icon="trash-2" class="text-error hover:bg-error/10" :title="__('common.delete')" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if ($resources->isNotEmpty())
            <x-slot:footer>
                {{ $resources->links() }}
            </x-slot:footer>
        @endif
    </x-ui.section>

</x-ui.page-layout>