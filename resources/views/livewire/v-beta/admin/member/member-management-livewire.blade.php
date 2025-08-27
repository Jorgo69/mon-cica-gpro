<main class="lg:ml-64 pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="p-6" wire:loading.class="opacity-50">

        <!-- Barre d'action -->
        <div class="mb-6 flex justify-between items-center">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Rechercher un membre..."
                class="form-input rounded-md shadow-sm mt-1 block w-full md:w-1/3 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">

            <button wire:click="openModal('create')" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                + Ajouter un membre
            </button>
        </div>

        <!-- Tableau des Membres -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                @if ($members->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400">Aucun membre trouvé.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer" wire:click="sortBy('name')">
                                    Nom
                                    @if ($sortField === 'name') <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span> @endif
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Téléphone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Rôle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Département</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Pays</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ville</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($members as $member)
                                <tr>
                                    <td class="px-6 py-4">{{ $member->name }}</td>
                                    <td class="px-6 py-4">{{ $member->email }}</td>
                                    <td class="px-6 py-4">{{ $member->telephone ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $member->role->name ?? $member->role }}</td>
                                    <td class="px-6 py-4">{{ $member->department->name ?? $member->department }}</td>
                                    <td class="px-6 py-4">{{ $member->pays ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $member->ville ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 space-x-2">
                                        <button wire:click="openModal('view', '{{ $member->id }}')" class="text-blue-500">Voir</button>
                                        <button wire:click="openModal('edit', '{{ $member->id }}')" class="text-green-500">Modifier</button>
                                        <button wire:click="openModal('delete', '{{ $member->id }}')" class="text-red-500">Supprimer</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $members->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                @if ($modalType === 'create')
                    <h2 class="text-xl mb-4">Ajouter un membre</h2>
                    @include('livewire.v-beta.admin.member.partials.form', ['action' => 'store'])

                @elseif ($modalType === 'edit')
                    <h2 class="text-xl mb-4">Modifier le membre</h2>
                    @include('livewire.v-beta.admin.member.partials.form', ['action' => 'update'])

                @elseif ($modalType === 'view')
                    <h2 class="text-xl mb-4">Détails du membre</h2>
                    <p><strong>Nom:</strong> {{ $name }}</p>
                    <p><strong>Email:</strong> {{ $email }}</p>
                    <p><strong>Téléphone:</strong> {{ $telephone }}</p>
                    <p><strong>Rôle:</strong> {{ $role }}</p>
                    <p><strong>Département:</strong> {{ $department }}</p>
                    <p><strong>Pays:</strong> {{ $pays }}</p>
                    <p><strong>Ville:</strong> {{ $ville }}</p>

                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-300 rounded">
                        Fermer
                    </button>

                @elseif ($modalType === 'delete')
                    <h2 class="text-xl mb-4 text-red-600">Confirmer la suppression</h2>
                    <p>Voulez-vous vraiment supprimer <strong>{{ $name }}</strong> ?</p>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-300 rounded">Annuler</button>
                        <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded">Supprimer</button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</main>
