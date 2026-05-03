<x-ui.page-layout>

    {{-- Header Section --}}
    <x-ui.page-header :title="__('projects.templates.title')" :subtitle="__('projects.templates.subtitle')">
        <x-slot:actions>
            <x-ui.button tag="a" :href="route('project.list')" variant="ghost" icon="arrow-left" size="sm" wire:navigate>
                {{ __('common.back') }}
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Search --}}
    <x-ui.card class="mb-6">
        <x-ui.input wire:model.live.debounce.300ms="search" :placeholder="__('projects.search_placeholder')" icon="search" />
    </x-ui.card>

    {{-- Flash Messages --}}
    @include('messages.index')

    {{-- Templates Grid --}}
    @if ($templates->isEmpty())
        <x-ui.empty-state
            icon="layout-template"
            :title="__('projects.templates.no_templates')"
            :description="__('projects.templates.no_templates_desc')"
        />
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($templates as $template)
                <x-ui.card class="relative flex flex-col justify-between hover:shadow-lg transition-shadow duration-200">
                    {{-- Template Type Badge --}}
                    <div class="absolute top-3 right-3">
                        @if (is_null($template->organization_id))
                            <x-ui.badge variant="blue" size="sm">
                                {{ __('projects.templates.system_template') }}
                            </x-ui.badge>
                        @else
                            <x-ui.badge variant="emerald" size="sm">
                                {{ __('projects.templates.org_template') }}
                            </x-ui.badge>
                        @endif
                    </div>

                    {{-- Card Body --}}
                    <div class="space-y-4">
                        {{-- Title --}}
                        <div class="pr-24">
                            <h3 class="text-base font-bold text-heading truncate">{{ $template->title }}</h3>
                            @if ($template->projectType)
                                <p class="text-xs text-muted mt-0.5">{{ $template->projectType->name }}</p>
                            @endif
                        </div>

                        {{-- Description --}}
                        @if ($template->description)
                            <p class="text-sm text-subtle line-clamp-2">{!! Str::limit(strip_tags($template->description), 120) !!}</p>
                        @endif

                        {{-- Stats --}}
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-1.5 text-xs text-muted">
                                <x-lucide-target class="w-3.5 h-3.5" />
                                <span>{{ __('projects.templates.objectives_count', ['count' => $template->computed_objectives_count]) }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs text-muted">
                                <x-lucide-list-checks class="w-3.5 h-3.5" />
                                <span>{{ __('projects.templates.activities_count', ['count' => $template->computed_activities_count]) }}</span>
                            </div>
                        </div>

                        {{-- Creator --}}
                        @if ($template->creator)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-surface-alt flex items-center justify-center text-subtle font-bold text-[10px]">
                                    {{ strtoupper(substr($template->creator->name ?? '?', 0, 1)) }}
                                </div>
                                <span class="text-[11px] text-muted">{{ $template->creator->name }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Card Actions --}}
                    <div class="mt-5 pt-4 border-t border-border-light dark:border-surface-alt flex items-center justify-between gap-2">
                        <x-ui.button
                            wire:click="duplicateFromTemplate('{{ $template->id }}')"
                            wire:loading.attr="disabled"
                            variant="accent"
                            icon="copy-plus"
                            size="sm"
                        >
                            <span wire:loading.remove wire:target="duplicateFromTemplate('{{ $template->id }}')">
                                {{ __('projects.templates.use') }}
                            </span>
                            <span wire:loading wire:target="duplicateFromTemplate('{{ $template->id }}')">
                                ...
                            </span>
                        </x-ui.button>

                        {{-- Toggle template (only for own projects) --}}
                        @if ($template->creator_user_id === auth()->id())
                            <x-ui.button
                                wire:click="toggleTemplate('{{ $template->id }}')"
                                wire:confirm="{{ __('projects.templates.unmark_template') }} ?"
                                variant="ghost"
                                icon="toggle-right"
                                size="sm"
                            >
                                {{ __('projects.templates.unmark_template') }}
                            </x-ui.button>
                        @endif
                    </div>
                </x-ui.card>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $templates->links() }}
        </div>
    @endif

</x-ui.page-layout>
