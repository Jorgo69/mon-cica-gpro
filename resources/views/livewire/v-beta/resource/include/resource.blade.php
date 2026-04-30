@if($activity->resources->count() > 0)
    <div class="bg-surface-alt p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold text-heading mb-4 flex items-center">
            <x-dynamic-component component="lucide-hand-coins" class="w-6 h-6 mr-3 text-accent inline" /> Ressources allouées
        </h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-surface">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-subtle uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-subtle uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-subtle uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-subtle uppercase tracking-wider">Coût Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-subtle uppercase tracking-wider">Responsable</th>
                        @can('create', $activity)
                        <th class="px-6 py-3 text-left text-xs font-medium text-subtle uppercase tracking-wider">Action</th>
                        @endcan
                    </tr>
                </thead>
                <tbody class="bg-card divide-y divide-border">
                    @foreach($activity->resources as $resource)
                        <tr class="hover:bg-surface">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-heading">{{ $resource->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-subtle">{{ $resource->type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-subtle">{{ $resource->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-subtle">{{ number_format($resource->total_cost, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-subtle">
                                @if ($resource->responsibleUser)
                                    {{ $resource->responsibleUser->name }}
                                @else
                                    Non assigné
                                @endif
                            </td>
                            @can('create', $activity)
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="openModal('{{ $resource->id }}')" class="text-accent hover:text-accent-dark">
                                    Modifier
                                </button>
                                
                                <button 
                                wire:click="deleteResource('{{ $resource->id }}')"
                                wire:confirm="Etes vous sur de vouloir supprimer cette resource?"
                                class="text-error hover:text-error-dark">
                                    Supprimer
                                </button>
                            </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
    
@else
    <div class="bg-surface-alt p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold text-heading mb-4 flex items-center">
            <x-dynamic-component component="lucide-hand-coins" class="w-6 h-6 mr-3 text-accent inline" /> Ressources allouées
        </h2>
        <p class="text-body">Aucune ressource n'a encore été ajoutée à cette activité.</p>
    </div>
@endif