<main class="lg:ml-64 pt-16 min-h-screen bg-surface">
    <div class="p-6" wire:loading.class="opacity-50">

        <div class="mb-6 flex justify-between items-center">
            

            {{-- Bouton Nouveau Projet --}}
            <a href="{{ route('creator.proposal.project.create') }}" class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-accent-dark active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                {{ __('sub_activities.new_project') }}
            </a>
        </div>

        {{-- Tableau des Projets --}}
        <div class="bg-card overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-heading">
                @if ($subActivities->isEmpty())
                    <p class="text-center text-subtle">{{ __('sub_activities.no_items') }}</p>
                @else
                    <table class="min-w-full divide-y divide-border dark:divide-border">
                        <thead class="bg-surface dark:bg-surface-alt">
                            <tr>
                                {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-body uppercase tracking-wider cursor-pointer" wire:click="sortBy('title')">
                                    Titre
                                    @if ($sortField === 'title')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-body uppercase tracking-wider cursor-pointer" wire:click="sortBy('project_code')">
                                    Code
                                    @if ($sortField === 'project_code')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-body uppercase tracking-wider cursor-pointer" wire:click="sortBy('status')">
                                    Statut
                                    @if ($sortField === 'status')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-body uppercase tracking-wider">
                                    Responsable
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-body uppercase tracking-wider cursor-pointer" wire:click="sortBy('start_date')">
                                    Début
                                    @if ($sortField === 'start_date')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-body uppercase tracking-wider cursor-pointer" wire:click="sortBy('end_date')">
                                    Fin
                                    @if ($sortField === 'end_date')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th> --}}
                            </tr>
                        </thead>
                        <tbody class="bg-card divide-y divide-border dark:divide-border">
                            @foreach ($subActivities as $activity)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-heading">
                                        {{ $activity->title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-body">
                                        {{ $activity->project_code }}
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
                                            {{  $activity->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-body">
                                        {{ $activity->creator->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-body">
                                        {{ \Carbon\Carbon::parse($activity->start_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-body">
                                        {{ \Carbon\Carbon::parse($activity->end_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('project.show', $activity->id) }}" class="text-accent hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 mr-2">{{ __('sub_activities.view') }}</a>
                                        <a href="{{ route('creator.proposal.project.edit', $activity->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">{{ __('sub_activities.edit') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- Pagination Livewire --}}
                    <div class="mt-4">
                        {{ $subActivities->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>