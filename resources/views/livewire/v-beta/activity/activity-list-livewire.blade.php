<main class="lg:ml-64 pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="p-6" wire:loading.class="opacity-50">

        <div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center gap-4">
        {{-- Champ de recherche --}}
        <div class="w-full md:w-1/3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher un projet..."
                class="form-input rounded-md shadow-sm block w-full dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2">
        </div>

        {{-- Filtre par Statut --}}
        <div class="w-full md:w-1/4">
            <select wire:model.live="statusFilter" class="form-select rounded-md shadow-sm block w-full dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2">
                <option value="">{{ __('table.status') }}</option>
                @foreach ($activityStatuses as $status)
                    <option value="{{ $status }}">{{ $status }}</option>
                    {{-- <option value="{{ $status }}">{{ Str::ucfirst(str_replace('_', ' ', $status == 'draft' ? 'Brouillons' : $status )) }}</option> --}}
                @endforeach
            </select>
        </div>

        {{-- Filtre par Responsable (Créateur du projet) --}}
        <div class="w-full md:w-1/4">
            <select wire:model.live="responsibleUserFilter" class="form-select rounded-md shadow-sm block w-full dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2">
                <option value="">{{ __('table.status') }}</option>
                @foreach ($availableUsers as $userOption)
                    <option value="{{ $userOption->id }}">{{ $userOption->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

        {{-- Tableau des Projets --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 overflow-auto">
                @if ($activities->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400">{{ __('table.no activities found for this selection') }}.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('description')">
                                    {{ __('table.description') }}
                                    @if ($sortField === 'description')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>

                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('description')">
                                    {{ __('table.budget') }}
                                    @if ($sortField === 'description')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('status')">
                                    {{ __('table.statut') }}
                                    @if ($sortField === 'status')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('table.responsible') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('start_date')">
                                    {{ __('table.start') }}
                                    @if ($sortField === 'start_date')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('end_date')">
                                    {{ __('table.end') }}
                                    @if ($sortField === 'end_date')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($activities as $activity)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ excerpt_words($activity->description). ' ...' }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activity->budget ?? 'N/A'}}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ 
                                            $activity->status === 'Actif' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' :
                                            ($activity->status === 'draft' ? 'bg-amber-300 text-amber-800 dark:bg-amber-900 dark:text-amber-200' :
                                            ($activity->status === 'Terminé' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                            ($activity->status === 'En attente' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                            )))
                                        }}
                                        ">
                                            {{ Str::ucfirst(str_replace('_', ' ', $activity->status == 'draft' ? 'Brouillon' : $activity->status )) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $activity->responsibleUser->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ friendly_date($activity->start_date) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ friendly_date($activity->end_date) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('project.show', $activity->project->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 mr-2">
                                            {{ __('table.preview') }}
                                        </a>
                                        <a href="{{ route('activity.management', $activity->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                            {{ __('table.manage') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- Pagination Livewire --}}
                    <div class="mt-4">
                        {{ $activities->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>