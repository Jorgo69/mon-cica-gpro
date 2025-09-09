<main class="lg:ml-64 pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="p-6" wire:loading.class="opacity-50">

        <div class="mb-6 space-y-4 md:space-y-0">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Filtres -->
                <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4 w-full md:flex-grow">
                    {{-- Champ de recherche --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('table.search project') }}..."
                            class="form-input rounded-md shadow-sm block w-full dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2">
                    </div>

                    {{-- Filtre par Statut --}}
                    <div class="w-full md:w-1/4">
                        <select wire:model.live="statusFilter" class="form-select rounded-md shadow-sm block w-full dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2">
                            <option value="">{{ __('table.status') }}</option>
                            @foreach ($projectStatuses as $status)
                                <option value="{{ $status }}">{{ Str::ucfirst(str_replace('_', ' ', $status == 'draft' ? 'Brouillons' : $status )) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtre par Responsable (Créateur du projet) --}}
                    <div class="w-full md:w-1/4">
                        <select wire:model.live="responsibleUserFilter" class="form-select rounded-md shadow-sm block w-full dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2">
                            <option value="">{{ __('table.responsibles') }}</option>
                            @foreach ($availableUsers as $userOption)
                                <option value="{{ $userOption->id }}">{{ $userOption->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Bouton Nouveau Projet --}}
                <div class="w-full md:w-auto">
                    <a href="{{ route('creator.proposal.project.create') }}" class="inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 w-full md:w-auto">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        {{ __('table.new project') }}
                    </a>
                </div>
            </div>
        </div>


        {{-- Message de Sucess --}}
        @include('messages.index')
        {{-- Message de Success End --}}

        {{-- Tableau des Projets --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 overflow-auto">
                @if ($projects->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400">{{ __('table.No projects found for this selection') }}.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('title')">
                                    {{ __('table.title') }}
                                    @if ($sortField === 'title')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('project_code')">
                                    {{ __('table.cod') }}
                                    @if ($sortField === 'project_code')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('status')">
                                    {{ __('table.status') }}
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
                            @foreach ($projects as $project)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $project->title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $project->project_code }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ 
                                            $project->status === 'Actif' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' :
                                            ($project->status === 'draft' ? 'bg-amber-300 text-amber-800 dark:bg-amber-900 dark:text-amber-200' :
                                            ($project->status === 'Terminé' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                            ($project->status === 'En attente' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                            )))
                                        }}
                                        ">
                                            {{ Str::ucfirst(str_replace('_', ' ', $project->status == 'draft' ? 'Brouillon' : $project->status )) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $project->creator->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}
                                    </td>

                                    @include('livewire.v-beta.project.include.link-project-list', [$project->id])
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- Pagination Livewire --}}
                    <div class="mt-4">
                        {{ $projects->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>