<x-ui.page-layout>

    <x-ui.page-header :title="__('admin.projects.title')" :subtitle="__('admin.projects.subtitle')" />

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-ui.input wire:model.live.debounce.300ms="search" :placeholder="__('admin.projects.search_placeholder')" icon="search" />

            <x-ui.select wire:model.live="statusFilter" icon="filter">
                <option value="">{{ __('admin.projects.all_statuses') }}</option>
                @foreach ($projectStatuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </x-ui.select>

            <x-ui.select wire:model.live="responsibleUserFilter" icon="user">
                <option value="">{{ __('admin.projects.all_responsibles') }}</option>
                @foreach ($availableUsers as $userOption)
                    <option value="{{ $userOption->id }}">{{ $userOption->name }}</option>
                @endforeach
            </x-ui.select>
        </div>
    </x-ui.card>

    {{-- Table --}}
    <x-ui.section :title="__('admin.projects.section')" icon="folder" :noPadding="false">
        <div class="overflow-x-auto -mx-6">
            @if ($projects->isEmpty())
                <x-ui.empty-state icon="folder-open" :title="__('admin.projects.no_projects')" :description="__('admin.projects.no_projects_desc')" />
            @else
                <table class="w-full">
                    <thead class="bg-surface-alt/50 bg-surface-alt/50">
                        <tr class="border-b border-border-light dark:border-surface-alt">
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest cursor-pointer group" wire:click="sortBy('title')">
                                <div class="flex items-center gap-1">{{ __('admin.projects.title_col') }} @if ($sortField === 'title') <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" /> @endif</div>
                            </th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest cursor-pointer group" wire:click="sortBy('project_code')">
                                <div class="flex items-center gap-1">{{ __('admin.projects.code') }} @if ($sortField === 'project_code') <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" /> @endif</div>
                            </th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest cursor-pointer group" wire:click="sortBy('status')">
                                <div class="flex items-center gap-1">{{ __('admin.projects.status') }} @if ($sortField === 'status') <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" /> @endif</div>
                            </th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">{{ __('admin.projects.responsible') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest cursor-pointer group" wire:click="sortBy('start_date')">
                                <div class="flex items-center gap-1">{{ __('admin.projects.start_date') }} @if ($sortField === 'start_date') <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" /> @endif</div>
                            </th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest cursor-pointer group" wire:click="sortBy('end_date')">
                                <div class="flex items-center gap-1">{{ __('admin.projects.end_date') }} @if ($sortField === 'end_date') <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" /> @endif</div>
                            </th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-muted uppercase tracking-widest">{{ __('admin.projects.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light dark:divide-surface-alt/50">
                        @foreach ($projects as $project)
                            <tr class="hover:bg-surface/50 dark:hover:bg-surface-alt/30 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-heading">{{ $project->title }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-mono font-bold text-subtle bg-surface-alt px-2 py-0.5 rounded-lg">{{ $project->project_code }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php $status = $project->status; @endphp
                                    <x-ui.badge :variant="$status?->color() ?? 'slate'" size="sm">
                                        {{ $status?->label() ?? $project->status }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4 text-sm text-subtle">{{ $project->creator->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-subtle">{{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm text-subtle">{{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        @include('livewire.v1.project.include.link-project-list')
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if($projects->isNotEmpty())
            <x-slot:footer>
                {{ $projects->links() }}
            </x-slot:footer>
        @endif
    </x-ui.section>

</x-ui.page-layout>