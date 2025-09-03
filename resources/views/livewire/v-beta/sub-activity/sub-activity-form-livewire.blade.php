<div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <form wire:submit.prevent="saveSubActivities">

        @if($editing)
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Modifier la sous-activité</h3>
        @else
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Ajouter une ou plusieurs sous-activités</h3>
        @endif

        @foreach ($subActivitiesData as $index => $subActivityData)
            <div class="relative p-5 border border-gray-200 dark:border-gray-600 rounded-lg mb-5 bg-gray-50 dark:bg-gray-700 shadow-sm">
                
                <!-- Bouton suppression (si plusieurs) -->
                @if(count($subActivitiesData) > 1 && !$editing)
                    <button 
                        type="button" 
                        wire:click="removeSubActivity({{ $index }})"
                        class="absolute top-3 right-3 text-red-500 hover:text-red-700 dark:hover:text-red-400 focus:outline-none"
                        aria-label="Supprimer cette sous-activité">
                        <i class="fas fa-times-circle text-lg"></i>
                    </button>
                @endif

                <!-- Grid : 2 colonnes sur md+, 1 sur mobile -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- Description (pleine largeur) -->
                    <div class="md:col-span-2">
                        <label for="description-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Description
                        </label>
                        <textarea 
                            id="description-{{ $index }}" 
                            wire:model.defer="subActivitiesData.{{ $index }}.description"
                            rows="3"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition ease-in-out duration-150"
                            placeholder="Décrivez cette sous-activité...">
                        </textarea>
                        @error('subActivitiesData.'.$index.'.description') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    
                    <!-- Date de début -->
                    <div>
                        <label for="start_date-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Date de début
                        </label>
                        <input 
                            type="date" 
                            id="start_date-{{ $index }}" 
                            wire:model.live="subActivitiesData.{{ $index }}.start_date"
                            min="{{ $activityStartDate }}"
                            max="{{ $activityEndDate }}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        @error('subActivitiesData.'.$index.'.start_date') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Date de fin -->
                    <div>
                        <label for="end_date-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Date de fin
                        </label>
                        <input 
                            type="date"
                            id="end_date-{{ $index }}" 
                            wire:model.live="subActivitiesData.{{ $index }}.end_date"
                            min="{{ $activityStartDate }}"
                            max="{{ $activityEndDate }}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        @error('subActivitiesData.'.$index.'.end_date') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Important (Switch Toggle) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Important
                        </label>
                        <div 
                            wire:click="toggleMilestone({{ $index }})"
                            x-data="{ on: @entangle('subActivitiesData.' . $index . '.is_milestone').live }"
                            role="checkbox"
                            :aria-checked="on"
                            class="relative inline-flex h-6 w-11 items-center rounded-full cursor-pointer transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'bg-blue-600': on, 'bg-gray-300': !on }"
                        >
                            <span 
                                class="inline-block h-4 w-4 transform rounded-full bg-white shadow-lg transition-transform" 
                                :class="{ 'translate-x-6': on, 'translate-x-1': !on }"
                            ></span>
                        </div>
                        <input 
                            type="hidden" 
                            wire:model.defer="subActivitiesData.{{ $index }}.is_milestone"
                        >
                        @error('subActivitiesData.'.$index.'.is_milestone') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Responsable -->
                    {{-- <div class="md:col-span-2"> --}}
                        <div>
                        <label for="responsible_user_id-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Responsable
                        </label>
                        <select 
                            id="responsible_user_id-{{ $index }}" 
                            wire:model.defer="subActivitiesData.{{ $index }}.responsible_user_id"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Sélectionner un responsable</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('subActivitiesData.'.$index.'.responsible_user_id') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                </div>
            </div>
        @endforeach

        <!-- Boutons -->
        <div class="flex flex-col sm:flex-row justify-between gap-3 mt-6">
            @if(!$editing)
                <button 
                    type="button" 
                    wire:click="addBlankSubActivity"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-md shadow hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800 transition ease-in-out duration-150"
                >
                    <i class="fas fa-plus mr-2"></i> Ajouter une sous-activité
                </button>
            @endif

            <button 
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150"
            >
                {{ $editing ? 'Mettre à jour' : 'Sauvegarder les sous-activités' }}
            </button>
        </div>
    </form>
</div>