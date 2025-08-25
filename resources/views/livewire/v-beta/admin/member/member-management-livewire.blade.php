<main class="lg:ml-64 pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="p-6" wire:loading.class="opacity-50">

        <div class="mb-6 flex justify-between items-center">
            <div class="flex-grow flex items-center space-x-4">
                {{-- Champ de recherche --}}
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher un projet..."
                    class="form-input rounded-md shadow-sm mt-1 block w-full md:w-1/3 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">


            </div>
            
        </div>

        {{-- Tableau des Projets --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                @if ($members->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400">Aucun projet trouvé pour cette sélection.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('title')">
                                    Nom complet
                                    @if ($sortField === 'title')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('project_code')">
                                    Email
                                    @if ($sortField === 'project_code')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('status')">
                                    Role
                                    @if ($sortField === 'status')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Departement
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('start_date')">
                                    Sexe
                                    @if ($sortField === 'start_date')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($members as $member)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $member->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $member->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ 
                                            $member->status === 'Actif' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' :
                                            ($member->status === 'draft' ? 'bg-amber-300 text-amber-800 dark:bg-amber-900 dark:text-amber-200' :
                                            ($member->status === 'Terminé' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                            ($member->status === 'En attente' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                            )))
                                        }}
                                        ">
                                            {{ $member->role->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $member->department->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $member->numero_identification ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        Action
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- Pagination Livewire --}}
                    <div class="mt-4">
                        {{ $members->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>