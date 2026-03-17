<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
    <div x-data="{ open: false }" @click.away="open = false" class="relative inline-block">
        <!-- Bouton avec les trois points -->
        <button
            type="button"
            @click="open = !open"
            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01" />
            </svg>
        </button>

        <!-- Menu déroulant qui s'adapte au contenu -->
        <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="fixed right-10 top-20 bg-white dark:bg-gray-800 shadow-lg rounded-md z-50 border dark:border-gray-700"
    >
            <div class="py-1">
                <a href="{{ route('project.show', $project->id) }}" wire:navigate
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-eye mr-2"></i> {{ __('table.preview') }}
                </a>

                <a href="{{ route('creator.proposal.project.edit', $project->id) }}" wire:navigate
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-pen mr-2"></i> {{ __('table.update') }}
                </a>

                {{-- @if (!in_array($project->status, ['draft', 'Brouillon']) || auth()->user()->role->name === 'Administrateur') --}}

                
                <a href="{{ route('project.dashboard', $project->id) }}" wire:navigate
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-chart-line mr-2"></i> {{ __('table.dashboard') }}
                </a>

                {{-- @endif --}}

                @can('delete', $project)
                <a href="#"  type="button"
                wire:click="deleteProject('{{ $project->id }}')"
                wire:confirm="Voudrez vous supprimez ce projet?, c'est irreversible"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-trash mr-2"></i> {{ __('table.delete') }}
                </a>
                @endcan
            </div>
        </div>


    </div>
</td>