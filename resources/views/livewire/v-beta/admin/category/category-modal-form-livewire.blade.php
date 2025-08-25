<div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    
    <form wire:submit.prevent="saveCategory">
     
        <div class="relative p-4 border rounded-lg mb-4 bg-gray-50 dark:bg-gray-700">
            

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Champ Nom --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                    <input type="text" id="name" wire:model.defer="name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                {{-- Champ Catégorie --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catégorie</label>
                    <input type="text" id="description" wire:model.defer="description" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

        </div>
        
        
        {{-- Boutons d'action pour les formulaires --}}
        <div class="flex justify-between mt-6">
            {{-- @if(!$editing)
            <button type="button" wire:click="addBlankResource" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-md shadow hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800">
                <i class="fas fa-plus mr-2"></i> Ajouter une autre ressource
            </button>
            @endif --}}
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ $editing ? ' Mettre a jour ' : ' Sauvegarder les ressources ' }}
            </button>
        </div>
    </form>
</div>
