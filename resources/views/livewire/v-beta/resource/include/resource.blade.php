@if($activity->resources->count() > 0)
    <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
            <i class="fas fa-hand-holding-usd mr-3 text-blue-600"></i> Ressources allouées
        </h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Coût Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Responsable</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($activity->resources as $resource)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $resource->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $resource->type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $resource->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format($resource->total_cost, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                @if ($resource->responsibleUser)
                                    {{ $resource->responsibleUser->name }}
                                @else
                                    Non assigné
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="openModal('{{ $resource->id }}')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                                    Modifier
                                </button>
                                {{-- Ajouter ici le bouton de suppression si nécessaire --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
    
@else
    <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
            <i class="fas fa-hand-holding-usd mr-3 text-blue-600"></i> Ressources allouées
        </h2>
        <p class="text-gray-700 dark:text-gray-300">Aucune ressource n'a encore été ajoutée à cette activité.</p>
    </div>
@endif