<main class="lg:ml-64 pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="p-6" wire:loading.class="opacity-50">
        <div class="mb-6 flex justify-between items-center">
            <div class="flex-grow flex items-center space-x-4">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher un projet..."
                    class="form-input rounded-md shadow-sm mt-1 block w-full md:w-1/3 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <a href="#" wire:click="openModal" type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                Nouvelle Categorie
            </a>
            
            @if ($showModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div wire:click="closeModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                    <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-h-full w-full max-w-2xl overflow-y-auto transform transition-all">
                        <div class="flex justify-end p-2">
                            <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Fermer</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="p-4 sm:p-6">
                            @livewire('v-beta.admin.category.category-modal-form-livewire', [
                                'editingCategoryId' => $editingCategoryId,
                            ])
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                @if ($categories->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400">Aucun projet trouvé pour cette sélection.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('name')">
                                    Nom
                                    @if ($sortField === 'name')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('description')">
                                    Description
                                    @if ($sortField === 'description')
                                        <span class="ml-1 text-sm">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $category->description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="openModal('{{ $category->id }}')"  class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                                            Modifier
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
