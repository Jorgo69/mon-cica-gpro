<div wire:ignore class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    
    {{-- Tableau des ressources existantes --}}
    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Ressources existantes</h3>
        
    {{-- Formulaire dynamique d'ajout de ressources --}}
    <form wire:submit.prevent="saveResources">
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Ajouter une ou plusieurs ressources</h3>

        @foreach ($newResources as $index => $newResource)
        <div class="relative p-4 border rounded-lg mb-4 bg-gray-50 dark:bg-gray-700">
            @if(count($newResources) > 1)
            <button type="button" wire:click="removeResource({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                <i class="fas fa-times-circle"></i>
            </button>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Champ Nom --}}
                <div>
                    <label for="name-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                    <input type="text" id="name-{{ $index }}" wire:model.defer="newResources.{{ $index }}.name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('newResources.{{ $index }}.name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                {{-- Champ Type --}}
                <div>
                    <label for="type-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                    <select id="type-{{ $index }}" wire:model.defer="newResources.{{ $index }}.type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Sélectionner un type</option>
                        <option value="Humain">Humain</option>
                        <option value="Materiel">Matériel</option>
                        <option value="Financier">Financier</option>
                    </select>
                    @error('newResources.{{ $index }}.type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                {{-- Champ Quantité --}}
                <div>
                    <label for="quantity-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantité</label>
                    <input type="number" id="quantity-{{ $index }}" wire:model="newResources.{{ $index }}.quantity" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('newResources.{{ $index }}.quantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                {{-- Champ Coût Unitaire --}}
                <div>
                    <label for="unit_cost-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Coût Unitaire</label>
                    <input type="number" step="0.01" id="unit_cost-{{ $index }}" wire:model="newResources.{{ $index }}.unit_cost" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('newResources.{{ $index }}.unit_cost') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                {{-- Champ Coût Total (affiché uniquement) --}}
                <div>
                    <label for="total_cost-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Coût Total</label>
                    <input type="text" id="total_cost-{{ $index }}" wire:model="newResources.{{ $index }}.total_cost" disabled class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm">
                </div>
                {{-- Champ Catégorie --}}
                <div>
                    <label for="category-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catégorie</label>
                    <input type="text" id="category-{{ $index }}" wire:model.defer="newResources.{{ $index }}.category" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('newResources.{{ $index }}.category') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                {{-- Champ Utilisateur Responsable --}}
                <div>
                    <label for="responsible_user_id-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Responsable</label>
                    <select id="responsible_user_id-{{ $index }}" wire:model.defer="newResources.{{ $index }}.responsible_user_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Sélectionner un responsable</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('newResources.{{ $index }}.responsible_user_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        @endforeach
        
        {{-- Boutons d'action pour les formulaires --}}
        <div class="flex justify-between mt-6">
            <button type="button" wire:click="addBlankResource" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-md shadow hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800">
                <i class="fas fa-plus mr-2"></i> Ajouter une autre ressource
            </button>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Sauvegarder les ressources
            </button>
        </div>
    </form>
</div>