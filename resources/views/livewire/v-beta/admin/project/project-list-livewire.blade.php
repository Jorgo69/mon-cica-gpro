<x-ui.page-layout>

    <x-ui.page-header title="Tous les Projets" subtitle="Vue d'administration de tous les projets du système" />

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-ui.input wire:model.live.debounce.300ms="search" placeholder="Rechercher un projet..." icon="search" />

            <x-ui.select wire:model.live="statusFilter" icon="filter">
                <option value="">Tous les statuts</option>
                @foreach ($projectStatuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
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

    {{-- Table --}}
    <x-ui.section title="Projets" icon="folder" :noPadding="false">
        <div class="overflow-x-auto -mx-6">
            @if ($projects->isEmpty())
                <x-ui.empty-state icon="folder-open" title="Aucun projet trouvé" description="Modifiez vos filtres ou attendez que des projets soient créés." />
            @else
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800">
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer group" wire:click="sortBy('title')">
                                <div class="flex items-center gap-1">Titre @if ($sortField === 'title') <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" /> @endif</div>
                            </th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer group" wire:click="sortBy('project_code')">
                                <div class="flex items-center gap-1">Code @if ($sortField === 'project_code') <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" /> @endif</div>
                            </th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer group" wire:click="sortBy('status')">
                                <div class="flex items-center gap-1">Statut @if ($sortField === 'status') <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" /> @endif</div>
                            </th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Responsable</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer group" wire:click="sortBy('start_date')">
                                <div class="flex items-center gap-1">Début @if ($sortField === 'start_date') <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" /> @endif</div>
                            </th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer group" wire:click="sortBy('end_date')">
                                <div class="flex items-center gap-1">Fin @if ($sortField === 'end_date') <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" /> @endif</div>
                            </th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @foreach ($projects as $project)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $project->title }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-lg">{{ $project->project_code }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php $status = $project->status; @endphp
                                    <x-ui.badge :variant="$status?->color() ?? 'slate'" size="sm">
                                        {{ $status?->label() ?? $project->status }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $project->creator->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        @include('livewire.v-beta.project.include.link-project-list')
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