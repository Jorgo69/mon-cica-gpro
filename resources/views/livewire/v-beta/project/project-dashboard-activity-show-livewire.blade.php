<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 py-8 text-center sm:block sm:p-0">
        
        <!-- Background Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
        <!-- This is a hack to center the modal content -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal Content -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-xl sm:w-full border border-gray-200 dark:border-gray-700">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                                Détails de l'activité
                            </h3>
                            <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-500 transition-colors duration-200">
                                <span class="sr-only">Close modal</span>
                                <x-dynamic-component component="lucide-x" class="w-5 h-5" />
                            </button>
                        </div>
                        
                        @if ($activity)
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-300 space-y-4">
                                <!-- Informations générales de l'activité -->
                                <div>
                                    <p class="font-semibold text-gray-700 dark:text-gray-100">Description :</p>
                                    <p>{{ $activity->description }}</p>
                                </div>
                                
                                <div>
                                    <p class="font-semibold text-gray-700 dark:text-gray-100">Responsable :</p>
                                    <p>{{ $activity->responsibleUser->name ?? 'Non assigné' }}</p>
                                </div>
                                
                                <div>
                                    <p class="font-semibold text-gray-700 dark:text-gray-100">Statut :</p>
                                    <p>{{ $activity->status }}</p>
                                </div>
                                
                                <div>
                                    <p class="font-semibold text-gray-700 dark:text-gray-100">Progression :</p>
                                    <p>{{ $activity->calculateProgress() }}%</p>
                                </div>

                                <!-- Section des ressources -->
                                @if ($activity->resources->count() > 0)
                                    <h4 class="text-md font-bold mt-6 mb-2 text-gray-900 dark:text-white">Ressources associées</h4>
                                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($activity->resources as $resource)
                                            <li class="py-3 flex items-center justify-between">
                                                <div>
                                                    <p class="font-medium text-gray-700 dark:text-gray-100">{{ $resource->description }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Responsable : {{ $resource->responsibleUser->name ?? 'Non assigné' }}</p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm italic text-gray-400 dark:text-gray-500 mt-4">Aucune ressource associée à cette activité.</p>
                                @endif

                                {{-- Historique de progression --}}
                                <h4 class="text-md font-bold mt-6 mb-2 text-gray-900 dark:text-white">Historique de progression</h4>
                                @livewire('v-beta.activity.activity-progress-history-livewire', ['activityId' => $activity->id], key('history-' . $activity->id))

                            </div>
                        @else
                            <p class="text-center text-sm italic text-gray-400 dark:text-gray-500">
                                Chargement des détails de l'activité...
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
