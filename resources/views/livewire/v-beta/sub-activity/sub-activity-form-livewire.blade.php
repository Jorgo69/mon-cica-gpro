<div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    
    <form wire:submit.prevent="saveSubActivities">

        {{-- @if($editing)
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Modifier la ressource</h3>
        @else
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Ajouter une ou plusieurs ressources</h3>
        @endif --}}
        
        @foreach ($subActivitiesData as $index => $subActivityData)
        <div class="relative p-4 border rounded-lg mb-4 bg-gray-50 dark:bg-gray-700">
            {{-- Le bouton de suppression n'apparaît que pour l'ajout de ressources --}}
            @if(count($subActivitiesData) > 1 && !$editing)
            <button type="button" wire:click="removeResource({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                <i class="fas fa-times-circle"></i>
            </button>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Champ Nom --}}
                <div>
                    <label for="description-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Activite a mene</label>
                    <input type="text" id="description-{{ $index }}" wire:model.defer="subActivitiesData.{{ $index }}.description" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('subActivitiesData.{{ $index }}.description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                {{-- Champ Type --}}
                <div>
                    <label for="is_milestone-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Important</label>
                    <select id="is_milestone-{{ $index }}" wire:model.defer="subActivitiesData.{{ $index }}.is_milestone" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Sélectionner un l'etat</option>
                        <option value="0">Pas Important</option>
                        <option value="1">Important</option>
                    </select>
                    @error('subActivitiesData.{{ $index }}.is_milestone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                {{-- Champ Date de debut --}}
                <div>
                    <label for="start_date-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de debut</label>
                    <input type="date" id="start_date-{{ $index }}" wire:model="subActivitiesData.{{ $index }}.start_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('subActivitiesData.{{ $index }}.start_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                {{-- Champ Date de fin  --}}
                <div>
                    <label for="end_date-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de fin</label>
                    <input type="date" id="end_date-{{ $index }}" wire:model="subActivitiesData.{{ $index }}.end_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm">
                </div>
                {{-- Champ Catégorie --}}
                <div>
                    <label for="quantity-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catégorie</label>
                    <input type="number" id="quantity-{{ $index }}" wire:model.defer="subActivitiesData.{{ $index }}.quantity" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('subActivitiesData.{{ $index }}.quantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                {{-- Champ Utilisateur Responsable --}}
                <div>
                    <label for="responsible_user_id-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Responsable</label>
                    <select id="responsible_user_id-{{ $index }}" wire:model.defer="subActivitiesData.{{ $index }}.responsible_user_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Sélectionner un responsable</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('subActivitiesData.{{ $index }}.responsible_user_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        @endforeach
        
        {{-- Boutons d'action pour les formulaires --}}
        <div class="flex justify-between mt-6">
            @if(!$editing)
            <button type="button" wire:click="addBlankSubActivity" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-md shadow hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800">
                <i class="fas fa-plus mr-2"></i> Ajouter un sous activité
            </button>
            @endif
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ $editing ? ' Mettre a jour ' : ' Sauvegarder les sous activités ' }}
            </button>
        </div>

    </form>
    
</div>
