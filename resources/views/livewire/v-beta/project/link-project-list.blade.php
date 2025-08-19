<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
    <div x-data="{ open: false }" @click.away="open = false" class="relative inline-block">
        <!-- Bouton avec les trois points -->
        
        <button type="button" @click="open = !open"
        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01" />
            </svg>
        </button>

        <!-- Menu déroulant -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute right-0 mt-2 w-18 bg-white dark:bg-gray-800 shadow-lg rounded-md overflow-hidden z-10 border dark:border-gray-700"
        >
            <div class="py-1">
                <a
                    href="{{ route('project.show', $project->id) }}"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                    <i class="fa-solid fa-eye"></i>
                </a>

                <a
                    href="{{ route('creator.proposal.project.edit', $project->id) }}"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                    <i class="fa-solid fa-eye-dropper"></i>
                    
                </a>
                <a
                    href="{{ route('project.dashboard', $project->id) }}"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                    <i class="fa-solid fa-list-check"></i>
                </a>

                <!-- Ajoute d'autres liens ici -->
                {{-- <a
                    href="{{ route('project.delete', $project->id) }}"
                    class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700"
                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')"
                >
                    Supprimer
                </a> --}}
            </div>
        </div>
    </div>
</td>