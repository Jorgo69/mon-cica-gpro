<main class="lg:ml-64 pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
    
    <div class="p-6">
        <div class="p-6 sm:px-20 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            @include('livewire.v-beta.activity.include.progres-bar')
            @if ($activity)
            {{-- En-tête de la page avec titre de l'activité et lien vers le projet --}}
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                <i class="fas fa-tasks text-blue-600 mr-2"></i> {{ $activity->description }}
            </h1>
            @if($activity->project)
            <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                Fais partie du projet : <span class="font-semibold">{{ $activity->project->title }}</span>
            </a>
            @endif

            <div class="mt-8 space-y-8">

                {{-- Section 1: Informations Générales de l'activité --}}
                <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-3 text-blue-600"></i> Informations Générales
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
                        <div>
                            <p><strong class="font-medium">Statut :</strong>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{
                                    $activity->status === 'En cours' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' :
                                    ($activity->status === 'Brouillon' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' :
                                    ($activity->status === 'Terminé' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                    ($activity->status === 'En Attente' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200')))
                                }}">
                                    {{ ucfirst($activity->status) }}
                                </span>
                            </p>
                            <p><strong class="font-medium">Date de Début :</strong> {{ $activity->start_date }}</p>
                            <p><strong class="font-medium">Date de Fin :</strong> {{ $activity->end_date }}</p>
                            <p><strong class="font-medium">Responsable :</strong>
                                @if($activity->responsibleUser)
                                    {{ $activity->responsibleUser->name }}
                                @else
                                    Non assigné
                                @endif
                            </p>
                            <p><strong class="font-medium">Budget alloué :</strong>
                                {{ $activity->budget ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p><strong class="font-medium">Créé :</strong> {{ $activity->created_at }}</p>
                            <p><strong class="font-medium">Dernière mise à jour :</strong> {{ $activity->updated_at }}</p>
                            @if($activity->result)
                            <div class="mt-4">
                                <strong class="font-medium dark:text-gray-100">Contribue au résultat :</strong>
                                <p class="mt-1 p-3 bg-gray-50 dark:bg-gray-900 rounded-md text-sm dark:text-gray-400">{{ $activity->result->description }}</p>
                            </div>
                            @endif
                        </div>
                        Createur Project: {{ $activity->result->specificObjective->logicalFramework->project->creator->name }} <br>
                        Responsable de l'Activite: {{ $activity->responsibleUser->name }} <br>
                    </div>
                </div>

                {{-- Section 2: Contexte du projet --}}
                @if($activity->project)
                <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <i class="fas fa-project-diagram mr-3 text-blue-600"></i> Contexte du Projet
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
                        <div>
                            <p><strong class="font-medium">Titre du Projet :</strong> {{ $activity->project->title }}</p>
                            <p><strong class="font-medium">Code du Projet :</strong> {{ $activity->project->project_code }}</p>
                            @if($activity->project->projectType)
                            <p><strong class="font-medium">Type de Projet :</strong> {{ $activity->project->projectType->name }}</p>
                            @endif
                        </div>
                        <div>
                            <p><strong class="font-medium">Durée du Projet :</strong> Du {{ $activity->project->start_date }} au {{ $activity->project->end_date }}</p>
                            <p><strong class="font-medium">Statut du Projet :</strong>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{
                                    $activity->project->status === 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' :
                                    ($activity->project->status === 'draft' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' :
                                    ($activity->project->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'))
                                }}">
                                    {{ ucfirst($activity->project->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Section 3: Gestion des Sous activites (espace réservé pour le formulaire dynamique) --}}
                <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <i class="fas fa-hand-holding-usd mr-3 text-blue-600"></i> Gestion des sous Activites
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300">
                        <p>Espace réservé pour la gestion des ressources de l'activité.</p>
                        {{-- <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition-colors">Ajouter une ressource</button> --}}
                        
                     
                        
                        {{-- Bouton pour ouvrir la modale --}}
                        @can('create', $activity)
                        <button wire:click="openModalForSubActivity" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition-colors">
                            Ajouter une sous activite
                        </button>
                        @endcan

                        {{-- La modale elle-même --}}
                        @if ($showModalForSubActivity)
                            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                {{-- Fond de la modale --}}
                                <div wire:click="closeModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

                                {{-- Contenu de la modale --}}
                                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-h-full w-full max-w-2xl overflow-y-auto transform transition-all">
                                    {{-- Bouton de fermeture --}}
                                    <div class="flex justify-end p-2">
                                        <button wire:click="closeModalForSubActivity" class="text-gray-400 hover:text-gray-500">
                                            <span class="sr-only">Fermer</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Le composant Livewire du formulaire --}}
                                    <div class="p-4 sm:p-6">
                                        @livewire('v-beta.sub-activity.sub-activity-form-livewire', [
                                            'activityId' => $activity->id,
                                            'subActivityToEditId' => $editingSubActivityId,
                                            ])
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                    </div>
                </div>

                {{-- Section 4: Ressources liées à l'activité --}}
                @include('livewire.v-beta.sub-activity.include.sub-activity')

                {{-- Section 4: Gestion des Ressources (espace réservé pour le formulaire dynamique) --}}
                <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <i class="fas fa-hand-holding-usd mr-3 text-blue-600"></i> Gestion des Ressources
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300">
                        <p>Espace réservé pour la gestion des ressources de l'activité.</p>
                        {{-- <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition-colors">Ajouter une ressource</button> --}}
                        

                        {{-- Bouton pour ouvrir la modale --}}
                        @can('create', $activity)
                        <button wire:click="openModal" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition-colors">
                            Ajouter une ressource
                        </button>
                        @endcan

                        {{-- La modale elle-même --}}
                        @if ($showModal)
                            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                {{-- Fond de la modale --}}
                                <div wire:click="closeModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

                                {{-- Contenu de la modale --}}
                                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-h-full w-full max-w-2xl overflow-y-auto transform transition-all">
                                    {{-- Bouton de fermeture --}}
                                    <div class="flex justify-end p-2">
                                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                                            <span class="sr-only">Fermer</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Le composant Livewire du formulaire --}}
                                    <div class="p-4 sm:p-6">
                                        @livewire('v-beta.resource.resource-form-livewire', [
                                            'activityId' => $activity->id,
                                            'resourceToEditId' => $editingResourceId,
                                            ])
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                    </div>
                </div>

                {{-- Section 5: Ressources liées à l'activité --}}
                @include('livewire.v-beta.resource.include.resource')
                
            </div>

            {{-- Boutons d'action --}}
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('activity.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Retour à la liste</a>
                {{-- <button type="button" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Modifier</button> --}}
            </div>
    
            @else
            <p class="text-gray-600 dark:text-gray-300">Activité non trouvée.</p>
            @endif
        </div>
        
    </div>
</main>
