<main class="lg:ml-64 pt-20 pb-12 min-h-screen bg-slate-50 dark:bg-slate-950">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8" wire:loading.class="opacity-60">
        
        {{-- Header Section --}}
        <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">
                    Liste des Projets
                </h1>
                <div class="mt-2 w-12 h-1 bg-accent rounded-full"></div>
                <p class="mt-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] italic">Gérez et suivez l'avancement de vos initiatives stratégiques</p>
            </div>

            <x-ui.button tag="a" :href="route('creator.proposal.project.create')" 
                        variant="accent" size="lg"
                        class="group">
                <i class="fas fa-plus-circle transition-transform group-hover:rotate-90 mr-3"></i>
                Nouveau Projet
            </x-ui.button>
        </header>

        {{-- Filters Section --}}
        <div class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Search --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Recherche</label>
                    <div class="relative group">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-accent transition-colors"></i>
                        <input type="text" wire:model.live.debounce.300ms="search" 
                               placeholder="Titre, code, mots-clés..."
                               class="w-full pl-12 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900 border-transparent dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:bg-white transition-all">
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Statut</label>
                    <select wire:model.live="statusFilter" 
                            class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-900 border-transparent dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:bg-white transition-all capitalize">
                        <option value="">Tous les statuts</option>
                        @foreach ($projectStatuses as $status)
                            <option value="{{ $status }}">{{ Str::ucfirst(str_replace('_', ' ', $status == 'draft' ? 'Brouillons' : $status )) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Responsible Filter --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Responsable</label>
                    <select wire:model.live="responsibleUserFilter" 
                            class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-900 border-transparent dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:bg-white transition-all">
                        <option value="">Tous les responsables</option>
                        @foreach ($availableUsers as $userOption)
                             <option value="{{ $userOption->id }}">{{ $userOption->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @include('messages.index')

        {{-- Projects Table Container --}}
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                @if ($projects->isEmpty())
                    <div class="p-20 text-center">
                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <i class="fas fa-folder-open text-2xl"></i>
                        </div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Aucun projet trouvé</p>
                        <p class="text-[10px] text-gray-400 italic mt-1">Essayez de modifier vos filtres ou de créer un nouveau projet.</p>
                    </div>
                @else
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
                                <th class="px-8 py-5 text-left cursor-pointer group" wire:click="sortBy('title')">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-accent transition-colors">Projet</span>
                                        @if ($sortField === 'title')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-accent text-[10px]"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-8 py-5 text-left cursor-pointer group" wire:click="sortBy('project_code')">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-accent transition-colors">Code</span>
                                        @if ($sortField === 'project_code')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-accent text-[10px]"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-8 py-5 text-left cursor-pointer group" wire:click="sortBy('status')">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-accent transition-colors">Statut</span>
                                        @if ($sortField === 'status')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-accent text-[10px]"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-8 py-5 text-left">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Responsable</span>
                                </th>
                                <th class="px-8 py-5 text-left cursor-pointer group" wire:click="sortBy('start_date')">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-accent transition-colors">Période</span>
                                        @if ($sortField === 'start_date')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-accent text-[10px]"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-8 py-5 text-right font-black text-[10px] text-slate-300 uppercase tracking-widest">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach ($projects as $project)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition-colors group">
                                    <td class="px-8 py-6">
                                        <div class="max-w-xs md:max-w-sm">
                                            <p class="text-sm font-black text-gray-800 dark:text-gray-100 truncate group-hover:text-indigo-600 transition-colors">{{ $project->title }}</p>
                                            <p class="text-[10px] text-gray-400 italic mt-0.5 truncate">{{ $project->short_title ?: 'Sans titre court' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-xs font-mono font-bold text-gray-500 bg-gray-100 dark:bg-gray-900 px-2.5 py-1 rounded-lg border border-gray-100 dark:border-gray-700">
                                            {{ $project->project_code }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        @php
                                            $statusVariants = [
                                                'Actif' => 'accent',
                                                'draft' => 'warning',
                                                'Terminé' => 'success',
                                                'En attente' => 'slate',
                                            ];
                                            $variant = $statusVariants[$project->status] ?? 'error';
                                        @endphp
                                        <x-ui.badge :variant="$variant" size="md">
                                            {{ $project->status == 'draft' ? 'Brouillon' : $project->status }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold text-[10px] border border-slate-200 dark:border-slate-700">
                                                {{ strtoupper(substr($project->creator->name ?? '?', 0, 1)) }}
                                            </div>
                                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ $project->creator->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[9px] font-black text-gray-300 uppercase tracking-tighter">Du</span>
                                                <span class="text-[11px] font-bold text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[9px] font-black text-gray-300 uppercase tracking-tighter">Au</span>
                                                <span class="text-[11px] font-bold text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end gap-2 outline-none">
                                            @include('livewire.v-beta.project.include.link-project-list', [$project->id])
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- Pagination Section --}}
            @if ($projects->isNotEmpty())
                <div class="px-8 py-6 bg-gray-50/30 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </div>
</main>