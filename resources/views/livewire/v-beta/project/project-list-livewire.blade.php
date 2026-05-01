<x-ui.page-layout>

    {{-- Header Section --}}
    <x-ui.page-header :title="__('projects.title')" :subtitle="__('projects.subtitle')">
        <x-slot:actions>
            <x-ui.button tag="a" :href="route('project.templates')" variant="outline" icon="layout-template" size="lg" wire:navigate>
                {{ __('projects.templates.title') }}
            </x-ui.button>
            <x-ui.button tag="a" :href="route('creator.proposal.project.create')" variant="accent" icon="plus-circle" size="lg" wire:navigate>
                {{ __('projects.new_project') }}
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-ui.input wire:model.live.debounce.300ms="search" :placeholder="__('projects.search_placeholder')" icon="search" />
            
            <x-ui.select wire:model.live="statusFilter" icon="filter">
                <option value="">{{ __('projects.all_statuses') }}</option>
                @foreach ($projectStatuses as $status)
                    @php
                        $statusEnum = $status instanceof \App\Enums\ProjectStatus ? $status : \App\Enums\ProjectStatus::tryFrom($status);
                        $label = $statusEnum ? $statusEnum->label() : Str::ucfirst(str_replace('_', ' ', (string) $status));
                        $val = $statusEnum ? $statusEnum->value : $status;
                    @endphp
                    <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
            </x-ui.select>

            <x-ui.select wire:model.live="responsibleUserFilter" icon="user">
                <option value="">{{ __('projects.all_responsibles') }}</option>
                @foreach ($availableUsers as $userOption)
                    <option value="{{ $userOption->id }}">{{ $userOption->name }}</option>
                @endforeach
            </x-ui.select>
        </div>
    </x-ui.card>

    {{-- Flash Messages --}}
    @include('messages.index')

    {{-- Projects Table --}}
    <x-ui.section title="Projets" icon="folder-kanban" :noPadding="false">
        <div class="overflow-x-auto -mx-6">
            @if ($projects->isEmpty())
                <x-ui.empty-state icon="folder-open" :title="__('projects.no_projects')" :description="__('projects.no_projects_desc')" />
            @else
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border-light dark:border-surface-alt">
                            <th class="px-6 py-3 text-left cursor-pointer group" wire:click="sortBy('title')">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-black text-muted uppercase tracking-widest group-hover:text-accent transition-colors">{{ __('projects.project') }}</span>
                                    @if ($sortField === 'title')
                                        <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left cursor-pointer group" wire:click="sortBy('project_code')">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-black text-muted uppercase tracking-widest group-hover:text-accent transition-colors">{{ __('projects.code') }}</span>
                                    @if ($sortField === 'project_code')
                                        <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left cursor-pointer group" wire:click="sortBy('status')">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-black text-muted uppercase tracking-widest group-hover:text-accent transition-colors">{{ __('projects.status') }}</span>
                                    @if ($sortField === 'status')
                                        <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-[10px] font-black text-muted uppercase tracking-widest">{{ __('projects.responsible') }}</span>
                            </th>
                            <th class="px-6 py-3 text-left cursor-pointer group" wire:click="sortBy('start_date')">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-black text-muted uppercase tracking-widest group-hover:text-accent transition-colors">{{ __('projects.period') }}</span>
                                    @if ($sortField === 'start_date')
                                        <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-body uppercase tracking-widest">{{ __('projects.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light dark:divide-surface-alt/50">
                        @foreach ($projects as $project)
                            <tr class="hover:bg-surface/50 dark:hover:bg-surface-alt/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="max-w-xs md:max-w-sm">
                                        <p class="text-sm font-bold text-heading truncate group-hover:text-accent transition-colors">{{ $project->title }}</p>
                                        <p class="text-[10px] text-muted italic mt-0.5 truncate">{{ $project->short_title ?: 'Sans titre court' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-mono font-bold text-subtle bg-surface-alt px-2.5 py-1 rounded-lg">
                                        {{ $project->project_code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusEnum = $project->status instanceof \App\Enums\ProjectStatus ? $project->status : \App\Enums\ProjectStatus::tryFrom($project->status);
                                        $variant = $statusEnum ? $statusEnum->color() : 'slate';
                                    @endphp
                                    <x-ui.badge :variant="$variant" size="md">
                                        {{ $statusEnum ? $statusEnum->label() : $project->status }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-surface-alt flex items-center justify-center text-subtle font-bold text-[10px]">
                                            {{ strtoupper(substr($project->creator->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="text-xs font-semibold text-subtle">{{ $project->creator->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[9px] font-black text-body uppercase">{{ __('projects.from') }}</span>
                                            <span class="text-[11px] font-semibold text-subtle">{{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[9px] font-black text-body uppercase">{{ __('projects.to') }}</span>
                                            <span class="text-[11px] font-semibold text-subtle">{{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        @include('livewire.v-beta.project.include.link-project-list', ['project' => $project])
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if ($projects->isNotEmpty())
            <x-slot:footer>
                {{ $projects->links() }}
            </x-slot:footer>
        @endif
    </x-ui.section>

</x-ui.page-layout>