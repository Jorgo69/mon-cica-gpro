{{-- Fenêtre 5 : Activités Initiales --}}
<div x-show="currentStep === 5" class="space-y-6">
    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Activités Initiales</h2>
    <p class="text-gray-600 dark:text-gray-300 mb-6">Listez les activités principales pour atteindre les résultats attendus.</p>

    <div class="space-y-4">
        @foreach($activities as $index => $activity)
            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg relative">
                <button type="button" wire:click="removeActivity({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-lg">&times;</button>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input type="hidden" wire:model="activities.{{ $index }}.id">
                        <label for="activity-description-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description de l'Activité <span class="text-red-500">*</span></label>
                        <textarea id="activity-description-{{ $index }}" wire:model.defer="activities.{{ $index }}.description" rows="2"
                                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Ex: Organiser des sessions de sensibilisation."></textarea>
                        @error('activities.' . $index . '.description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="activity-responsible-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Responsable <span class="text-red-500">*</span></label>
                        <select id="activity-responsible-{{ $index }}" wire:model.defer="activities.{{ $index }}.responsible_user_id"
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Sélectionner un responsable</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('activities.' . $index . '.responsible_user_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="activity-start-date-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de Début <span class="text-red-500">*</span></label>
                        <input type="date" id="activity-start-date-{{ $index }}" wire:model.defer="activities.{{ $index }}.start_date"
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                        @error('activities.' . $index . '.start_date') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="activity-end-date-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de Fin <span class="text-red-500">*</span></label>
                        <input type="date" id="activity-end-date-{{ $index }}" wire:model.defer="activities.{{ $index }}.end_date"
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                        @error('activities.' . $index . '.end_date') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="activity-status-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut <span class="text-red-500">*</span></label>
                        <select id="activity-status-{{ $index }}" wire:model.defer="activities.{{ $index }}.status"
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                            <option value="En cours">En cours</option>
                            <option value="Terminée">Terminée</option>
                            <option value="En attente">En attente</option>
                            <option value="En retard">En retard</option>
                        </select>
                        @error('activities.' . $index . '.status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="activity-budget-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Budget (Optionnel)</label>
                        {{-- <textarea id="activity-justification-{{ $index }}" wire:model.defer="activities.{{ $index }}.justification" rows="1"
                                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Budget prevu pour cette activité."></textarea> --}}
                        <input type="number"
                        id="activity-budget-{{ $index }}" wire:model.defer="activities.{{ $index }}.budget" rows="1"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Budget prevu pour cette activité.">
                    </div>
                    <div class="flex items-center col-span-full">
                        <input type="checkbox" id="activity-milestone-{{ $index }}" wire:model.defer="activities.{{ $index }}.is_milestone"
                                class="rounded border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-900 dark:checked:bg-blue-600">
                        <label for="activity-milestone-{{ $index }}" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Jalon important</label>
                    </div>
                </div>
            </div>
        @endforeach
        <button type="button" wire:click="addActivity" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold dark:bg-blue-600 dark:text-gray-100 dark:hover:bg-blue-700">
            Ajouter une Activité
        </button>
    </div>
    
</div>