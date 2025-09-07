<main class="lg:ml-64 pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="p-4 md:p-6 space-y-6 md:space-y-10" wire:loading.class="opacity-50">

        <!-- Table Users -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white">Membres supprimés</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <input type="checkbox" wire:model="selectAllUsers" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rôle</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Supprimé le</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($trashedUsers as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="checkbox" wire:model="selectedIds" value="{{ $user->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $user->email }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $user->role }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $user->deleted_at }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <div class="flex flex-wrap gap-2">
                                        <button wire:click="openModal('view','user','{{ $user->id }}')" class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 rounded-md text-xs font-medium hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                            Voir
                                        </button>
                                        <button wire:click="restore('{{ $user->id }}','user')" class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-md text-xs font-medium hover:bg-green-200 dark:hover:bg-green-800 transition-colors">
                                            Restaurer
                                        </button>
                                        <button wire:click="openModal('delete','user','{{ $user->id }}')" class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-md text-xs font-medium hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                                            Supprimer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Aucun membre supprimé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $trashedUsers->links() }}
            </div>
        </div>

        <!-- Table Project Types -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white">Types de projet supprimés</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <input type="checkbox" wire:model="selectAllTypes" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Catégorie</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Supprimé le</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($trashedProjectTypes as $type)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="checkbox" wire:model="selectedIds" value="{{ $type->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $type->name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $type->category }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $type->deleted_at }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <div class="flex flex-wrap gap-2">
                                        <button wire:click="openModal('view','project_type','{{ $type->id }}')" class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 rounded-md text-xs font-medium hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                            Voir
                                        </button>
                                        <button wire:click="restore('{{ $type->id }}','project_type')" class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-md text-xs font-medium hover:bg-green-200 dark:hover:bg-green-800 transition-colors">
                                            Restaurer
                                        </button>
                                        <button wire:click="openModal('delete','project_type','{{ $type->id }}')" class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-md text-xs font-medium hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                                            Supprimer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Aucun type supprimé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $trashedProjectTypes->links() }}
            </div>
        </div>

        <!-- Table Projects -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white">Projets supprimés</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <input type="checkbox" wire:model="selectAllProjects" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Titre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Supprimé le</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($trashedProjects as $project)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="checkbox" wire:model="selectedIds" value="{{ $project->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $project->title }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $project->project_code }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $project->status }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $project->deleted_at }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <div class="flex flex-wrap gap-2">
                                        <button wire:click="openModal('view','project','{{ $project->id }}')" class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 rounded-md text-xs font-medium hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                            Voir
                                        </button>
                                        <button wire:click="restore('{{ $project->id }}','project')" class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-md text-xs font-medium hover:bg-green-200 dark:hover:bg-green-800 transition-colors">
                                            Restaurer
                                        </button>
                                        <button wire:click="openModal('delete','project','{{ $project->id }}')" class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-md text-xs font-medium hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                                            Supprimer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Aucun projet supprimé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $trashedProjects->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                @if ($modalType === 'view' && $selectedItem)
                    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Détails supprimé</h2>
                    <pre class="text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 p-4 rounded-md overflow-x-auto">{{ json_encode($selectedItem, JSON_PRETTY_PRINT) }}</pre>
                    <div class="mt-4 flex justify-end">
                        <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                            Fermer
                        </button>
                    </div>

                @elseif ($modalType === 'delete' && $selectedItem)
                    <h2 class="text-xl font-bold mb-4 text-red-600 dark:text-red-400">Suppression définitive</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">Voulez-vous vraiment supprimer définitivement cet élément ?</p>
                    <div class="mt-4 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                        <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                            Annuler
                        </button>
                        <button wire:click="forceDelete('{{ $selectedItem->id }}','{{ $selectedModel }}')" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                            Supprimer définitivement
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</main>