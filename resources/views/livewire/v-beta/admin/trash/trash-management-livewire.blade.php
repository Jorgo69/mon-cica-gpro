<main class="lg:ml-64 pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="p-6 space-y-10" wire:loading.class="opacity-50">

        <!-- Table Users -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <h2 class="text-lg font-bold p-4">Membres supprimés</h2>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th><input type="checkbox" wire:model="selectAllUsers"></th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Supprimé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trashedUsers as $user)
                        <tr>
                            <td><input type="checkbox" wire:model="selectedIds" value="{{ $user->id }}"></td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->deleted_at }}</td>
                            <td class="space-x-2">
                                <button wire:click="openModal('view','user','{{ $user->id }}')" class="text-blue-500">Voir</button>
                                <button wire:click="restore('{{ $user->id }}','user')" class="text-green-500">Restaurer</button>
                                <button wire:click="openModal('delete','user','{{ $user->id }}')" class="text-red-500">Supprimer</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-gray-500">Aucun membre supprimé</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $trashedUsers->links() }}
        </div>

        <!-- Table Project Types -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <h2 class="text-lg font-bold p-4">Types de projet supprimés</h2>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th><input type="checkbox" wire:model="selectAllTypes"></th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Supprimé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trashedProjectTypes as $type)
                        <tr>
                            <td><input type="checkbox" wire:model="selectedIds" value="{{ $type->id }}"></td>
                            <td>{{ $type->name }}</td>
                            <td>{{ $type->category }}</td>
                            <td>{{ $type->deleted_at }}</td>
                            <td class="space-x-2">
                                <button wire:click="openModal('view','project_type','{{ $type->id }}')" class="text-blue-500">Voir</button>
                                <button wire:click="restore('{{ $type->id }}','project_type')" class="text-green-500">Restaurer</button>
                                <button wire:click="openModal('delete','project_type','{{ $type->id }}')" class="text-red-500">Supprimer</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-gray-500">Aucun type supprimé</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $trashedProjectTypes->links() }}
        </div>

        <!-- Table Projects -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <h2 class="text-lg font-bold p-4">Projets supprimés</h2>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th><input type="checkbox" wire:model="selectAllProjects"></th>
                        <th>Titre</th>
                        <th>Code</th>
                        <th>Statut</th>
                        <th>Supprimé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trashedProjects as $project)
                        <tr>
                            <td><input type="checkbox" wire:model="selectedIds" value="{{ $project->id }}"></td>
                            <td>{{ $project->title }}</td>
                            <td>{{ $project->project_code }}</td>
                            <td>{{ $project->status }}</td>
                            <td>{{ $project->deleted_at }}</td>
                            <td class="space-x-2">
                                <button wire:click="openModal('view','project','{{ $project->id }}')" class="text-blue-500">Voir</button>
                                <button wire:click="restore('{{ $project->id }}','project')" class="text-green-500">Restaurer</button>
                                <button wire:click="openModal('delete','project','{{ $project->id }}')" class="text-red-500">Supprimer</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-gray-500">Aucun projet supprimé</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $trashedProjects->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-lg">
                @if ($modalType === 'view' && $selectedItem)
                    <h2 class="text-xl mb-4">Détails supprimé</h2>
                    <pre class="text-sm text-gray-700 dark:text-gray-300">{{ json_encode($selectedItem, JSON_PRETTY_PRINT) }}</pre>

                @elseif ($modalType === 'delete' && $selectedItem)
                    <h2 class="text-xl mb-4 text-red-600">Suppression définitive</h2>
                    <p>Voulez-vous vraiment supprimer définitivement <strong>{{ $selectedItem->id }}</strong> ?</p>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-300 rounded">Annuler</button>
                        <button wire:click="forceDelete('{{ $selectedItem->id }}','{{ $selectedModel }}')" class="px-4 py-2 bg-red-600 text-white rounded">Supprimer</button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</main>
