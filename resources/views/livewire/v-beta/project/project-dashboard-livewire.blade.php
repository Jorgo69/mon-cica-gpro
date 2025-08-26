<main class="lg:ml-64 pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="p-6 sm:p-10 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans antialiased text-gray-900 dark:text-gray-100 transition-colors duration-300 ease-in-out">
        
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 dark:text-white mb-6">
                Tableau de bord du projet : {{ $project->title }}
            </h1>
            
            <!-- Section de la barre de progression -->
            @include('livewire.v-beta.project.include.progres-bar')
            
            <!-- Section des indicateurs clés -->
            @php
                $totalActivitiesCount = $allActivities->count();

                $completedActivitiesCount = $allActivities->filter(fn($a) => $a->calculateProgress() >= 100)->count();

                $nonStartedCount = $allActivities->filter(fn($a) => $a->calculateProgress() === 0)->count();

                $ongoingCount = $allActivities->filter(fn($a) => $a->calculateProgress() > 0 && $a->calculateProgress() < 100)->count();

                // 🔴 En retard : progression < progression attendue selon la date
                $lateActivitiesCount = $allActivities->filter(function ($activity) {
                    $plannedProgress = $activity->getPlannedProgressPercentage(); // Ce qu'on devrait avoir aujourd'hui
                    $actualProgress = $activity->calculateProgress();

                    return $actualProgress < $plannedProgress && $plannedProgress > 0;
                })->count();
            @endphp
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 my-6">
                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalActivitiesCount }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Total</div>
                </div>

                <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-green-800 dark:text-green-200">{{ $completedActivitiesCount }}</div>
                    <div class="text-sm text-green-700 dark:text-green-300">Terminées</div>
                </div>

                <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-blue-800 dark:text-blue-200">{{ $ongoingCount }}</div>
                    <div class="text-sm text-blue-700 dark:text-blue-300">En cours</div>
                </div>

                <div class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-yellow-800 dark:text-yellow-200">{{ $nonStartedCount }}</div>
                    <div class="text-sm text-yellow-700 dark:text-yellow-300">Non démarrées</div>
                </div>

                <div class="bg-red-100 dark:bg-red-900 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-red-800 dark:text-red-200">{{ $lateActivitiesCount }}</div>
                    <div class="text-sm text-red-700 dark:text-red-300">En retard</div>
                </div>
            </div>
            
            <!-- Section de recherche et de filtres -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6 border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                <div class="relative w-full md:w-1/3">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher une activité..."
                           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 dark:text-gray-400"></i>
                    </div>
                </div>
                <div class="flex-grow flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0">
                    <div class="w-full md:w-1/3">
                        <select wire:model="responsibleUserFilter" class="w-full py-2 px-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Tous les responsables</option>
                            @foreach ($availableUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-1/3">
                        <select wire:model="statusFilter" class="w-full py-2 px-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Tous les statuts</option>
                            <option value="En cours">En cours</option>
                            <option value="Terminé">Terminé</option>
                            <option value="En retard">En retard</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/3">
                        <select wire:model="perPage" class="w-full py-2 px-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="10">10 par page</option>
                            <option value="25">25 par page</option>
                            <option value="50">50 par page</option>
                            {{-- <option value="{{ $totalActivitiesCount }}">Tout afficher</option> --}}
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Tableau des activités -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 overflow-hidden">
                <h2 class="text-2xl font-bold mb-4">Détails des activités</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Responsable</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Progression</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($activities as $activity)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-normal text-sm font-medium text-gray-900 dark:text-gray-100">{{ $activity->description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $activity->responsibleUser->name ?? 'Non assigné' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($activity->status == 'Terminé') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200
                                            @elseif($activity->status == 'En cours') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200
                                            @elseif($activity->status == 'En retard') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                            @endif">
                                            {{ $activity->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $activity->calculateProgress() }}%</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="openActivityDetails('{{ $activity->id }}')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                                            <i class="fas fa-eye text-lg"></i>
                                        </button>
                                        <a href="{{ route('activity.management', ['activity' => $activity->id]) }}" class="text-indigo-600 ml-5 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                                            <i class="fa-solid fa-plus"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Aucune activité n'a été trouvée pour ce projet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Render the modal component if an activity is selected -->
    @if($selectedActivityId)
        @livewire('v-beta.project.project-dashboard-activity-show-livewire', ['activityId' => $selectedActivityId])
    @endif
</main>
