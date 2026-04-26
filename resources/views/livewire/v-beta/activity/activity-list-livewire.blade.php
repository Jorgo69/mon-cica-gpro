<x-ui.page-layout>

    {{-- Header Section --}}
    <x-ui.page-header title="Liste des Activités" subtitle="Gérez et suivez l'avancement de vos activités">
        {{-- If there were actions like 'Nouvelle Activité', they would go here --}}
    </x-ui.page-header>

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-ui.input wire:model.live.debounce.300ms="search" placeholder="Rechercher par projet lié..." icon="search" />
            
            <x-ui.select wire:model.live="statusFilter" icon="filter">
                <option value="">{{ __('table.status') }}</option>
                @foreach ($activityStatuses as $status)
                    <option value="{{ $status }}">{{ Str::ucfirst(str_replace('_', ' ', strtolower($status) == 'draft' ? 'Brouillon' : $status )) }}</option>
                @endforeach
            </x-ui.select>

            <x-ui.select wire:model.live="responsibleUserFilter" icon="user">
                <option value="">Tous les responsables</option>
                @foreach ($availableUsers as $userOption)
                    <option value="{{ $userOption->id }}">{{ $userOption->name }}</option>
                @endforeach
            </x-ui.select>
        </div>
    </x-ui.card>

    {{-- Flash Messages --}}
    @include('messages.index')

    {{-- Activities Table --}}
    <x-ui.section title="Activités" icon="list-todo" :noPadding="false">
        <div class="overflow-x-auto -mx-6">
            @if (!isset($activities) || $activities->isEmpty())
                <x-ui.empty-state icon="list-todo" title="Aucune activité trouvée" description="Essayez de modifier vos filtres pour voir les résultats." />
            @else
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border-light dark:border-surface-alt">
                            <th class="px-6 py-3 text-left cursor-pointer group" wire:click="sortBy('description')">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-black text-muted uppercase tracking-widest group-hover:text-accent transition-colors">{{ __('table.description') }}</span>
                                    @if ($sortField === 'description')
                                        <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-[10px] font-black text-muted uppercase tracking-widest">{{ __('table.budget') }}</span>
                            </th>
                            <th class="px-6 py-3 text-left cursor-pointer group" wire:click="sortBy('status')">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-black text-muted uppercase tracking-widest group-hover:text-accent transition-colors">{{ __('table.statut') }}</span>
                                    @if ($sortField === 'status')
                                        <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-[10px] font-black text-muted uppercase tracking-widest">{{ __('table.responsible') }}</span>
                            </th>
                            <th class="px-6 py-3 text-left cursor-pointer group" wire:click="sortBy('start_date')">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-black text-muted uppercase tracking-widest group-hover:text-accent transition-colors">Période</span>
                                    @if ($sortField === 'start_date' || $sortField === 'end_date')
                                        <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-body uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light dark:divide-surface-alt/50">
                        @foreach ($activities as $activity)
                            <tr class="hover:bg-surface/50 dark:hover:bg-surface-alt/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="max-w-xs md:max-w-sm">
                                        <p class="text-sm font-bold text-heading truncate group-hover:text-accent transition-colors" title="{{ $activity->description }}">
                                            {{ excerpt_words($activity->description, 10). ' ...' }}
                                        </p>
                                        <p class="text-[10px] text-muted italic mt-0.5 truncate">
                                            Projet: {{ $activity->result?->specificObjective?->logicalFramework?->project?->short_title ?? 'N/A' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-body">
                                    {{ is_numeric($activity->budget) ? number_format($activity->budget, 0, ',', ' ') : ($activity->budget ?? 'N/A') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusStr = strtolower($activity->status ?? '');
                                        $variant = match(true) {
                                            $statusStr === 'actif' => 'emerald',
                                            in_array($statusStr, ['draft', 'brouillon']) => 'amber',
                                            in_array($statusStr, ['terminé', 'termine']) => 'blue',
                                            $statusStr === 'en attente' => 'yellow',
                                            default => 'red'
                                        };
                                    @endphp
                                    <x-ui.badge :variant="$variant" size="md">
                                        {{ Str::ucfirst(str_replace('_', ' ', $statusStr == 'draft' ? 'Brouillon' : ($activity->status ?: 'N/A') )) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-surface-alt flex items-center justify-center text-subtle font-bold text-[10px]">
                                            {{ strtoupper(substr($activity->responsibleUser->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="text-xs font-semibold text-subtle">{{ $activity->responsibleUser->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[9px] font-black text-body uppercase">Du</span>
                                            <span class="text-[11px] font-semibold text-subtle">{{ $activity->start_date ? \Carbon\Carbon::parse($activity->start_date)->format('d/m/Y') : 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[9px] font-black text-body uppercase">Au</span>
                                            <span class="text-[11px] font-semibold text-subtle">{{ $activity->end_date ? \Carbon\Carbon::parse($activity->end_date)->format('d/m/Y') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($activity->result?->specificObjective?->logicalFramework?->project_id)
                                            <a href="{{ route('project.show', $activity->result->specificObjective->logicalFramework->project_id) }}" class="text-xs font-semibold text-muted hover:text-accent transition-colors" title="{{ __('table.preview') }}">
                                                <x-lucide-eye class="w-4 h-4" />
                                            </a>
                                        @endif
                                        <a href="{{ route('activity.management', $activity->id) }}" class="text-xs font-semibold text-muted hover:text-accent transition-colors" title="{{ __('table.manage') }}">
                                            <x-lucide-settings class="w-4 h-4" />
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if (isset($activities) && $activities->isNotEmpty())
            <x-slot:footer>
                {{ $activities->links() }}
            </x-slot:footer>
        @endif
    </x-ui.section>

</x-ui.page-layout>