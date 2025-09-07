<main class="lg:ml-64 pt-16 lg:pt-24 min-h-screen bg-gray-50 dark:bg-gray-900 px-4 sm:px-6 lg:px-8 py-6">
    <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">
        <!-- En-tête -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                Types de Projets
            </h2>
            <a href="{{ route('admin.it.project.types.create') }}" class="w-full sm:w-auto px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-colors text-center">
                Créer un nouveau type
            </a>
        </div>
        
        <!-- Messages d'alerte -->
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm rounded-lg bg-green-100 text-green-800" role="alert">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="p-4 mb-4 text-sm rounded-lg bg-red-100 text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Grille des types de projets -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @forelse ($projectTypes as $type)
                <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $type->name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">{{ $type->description }}</p>
                    <div class="mt-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                        <span class="text-xs font-medium text-blue-600 dark:text-blue-400">Catégorie: {{ $type->category ?? 'N/A' }}</span>
                        <div class="flex flex-wrap gap-2">
                            {{-- <a href="{{ route('admin.it.type.of.project.show', ['projectTypeId' => $type->id]) }}" class="px-3 py-1.5 text-xs sm:text-sm font-medium rounded-lg text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors border border-blue-200 dark:border-blue-800">
                                Voir
                            </a> --}}
                            <a href="{{ route('admin.it.project.types.edit', ['projectTypeId' => $type->id]) }}" class="px-3 py-1.5 text-xs sm:text-sm font-medium rounded-lg text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors border border-blue-200 dark:border-blue-800">
                                Éditer
                            </a>
                            
                            <button wire:click="deleteProjectType('{{ $type->id }}')" onclick="confirm('Êtes-vous sûr de vouloir supprimer ce type de projet ?') || event.stopImmediatePropagation()" class="px-3 py-1.5 text-xs sm:text-sm font-medium rounded-lg text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors border border-red-200 dark:border-red-800">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <p class="text-gray-700 dark:text-gray-300">Aucun type de projet n'a encore été créé.</p>
                </div>
            @endforelse
        </div>

    </div>
    
    <style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    </style>
</main>

