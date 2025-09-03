{{-- Fenêtre 3 : Cadre Logique (But & Objectifs Spécifiques) --}}
<div x-show="currentStep === 3" class="space-y-6">
    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Cadre Logique du Projet</h2>
    <p class="text-gray-600 dark:text-gray-300 mb-6">Définissez le but général et les objectifs spécifiques de votre projet.</p>

    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">But Général</h3>
    <div>
        <label for="general_objective" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Objectif Général <span class="text-red-500">*</span></label>
        <textarea id="general_objective" wire:model.defer="initialLogicalFramework.general_objective" rows="3"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Ex: Contribuer à l'amélioration de la santé maternelle et infantile dans la région X."></textarea>
        @error('initialLogicalFramework.general_objective') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>
    <div>
        <label for="general_obj_indicators" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Indicateurs de l'Objectif Général (Optionnel)</label>
        <textarea id="general_obj_indicators" wire:model.defer="initialLogicalFramework.general_obj_indicators" rows="2"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Ex: Réduction de 15% du taux de mortalité infantile d'ici 2025."></textarea>
        @error('initialLogicalFramework.general_obj_indicators') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>
    <div>
        <label for="general_obj_verification_sources" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sources de Vérification (Optionnel)</label>
        <input type="text" id="general_obj_verification_sources" wire:model.defer="initialLogicalFramework.general_obj_verification_sources"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Ex: Rapports du ministère de la Santé.">
        @error('initialLogicalFramework.general_obj_verification_sources') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>
    <div>
        <label for="assumptions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hypothèses (Optionnel)</label>
        <textarea id="assumptions" wire:model.defer="initialLogicalFramework.assumptions" rows="2"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Ex: Stabilité politique de la région."></textarea>
        @error('initialLogicalFramework.assumptions') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">Objectifs Spécifiques</h3>
    <div class="space-y-4">
        @foreach($specificObjectives as $index => $objective)
            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg relative">
                <button type="button" wire:click="removeSpecificObjective({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-lg">&times;</button>
                <input type="hidden" wire:model="specificObjectives.{{ $index }}.id">
                <div>
                    <label for="specific-objective-description-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description <span class="text-red-500">*</span></label>
                    <textarea id="specific-objective-description-{{ $index }}" wire:model.defer="specificObjectives.{{ $index }}.description" rows="2"
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Ex: Augmenter l'accès aux soins prénatals pour les femmes enceintes de 30% en 1 an."></textarea>
                    @error('specificObjectives.' . $index . '.description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="specific-objective-indicators-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Indicateurs (Optionnel)</label>
                    <textarea id="specific-objective-indicators-{{ $index }}" wire:model.defer="specificObjectives.{{ $index }}.indicators" rows="2"
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Ex: Nombre de consultations prénatales effectuées."></textarea>
                    @error('specificObjectives.' . $index . '.indicators') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="specific-objective-verification-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sources de Vérification (Optionnel)</label>
                    <input type="text" id="specific-objective-verification-{{ $index }}" wire:model.defer="specificObjectives.{{ $index }}.verification_sources"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Ex: Registres des centres de santé.">
                    @error('specificObjectives.' . $index . '.verification_sources') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="specific-objective-assumptions-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hypothèses (Optionnel)</label>
                    <textarea id="specific-objective-assumptions-{{ $index }}" wire:model.defer="specificObjectives.{{ $index }}.assumptions" rows="2"
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Ex: Disponibilité des personnels de santé."></textarea>
                    @error('specificObjectives.' . $index . '.assumptions') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        @endforeach
        <button type="button" wire:click="addSpecificObjective" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold dark:bg-blue-600 dark:text-gray-100 dark:hover:bg-blue-700">
            Ajouter un Objectif Spécifique
        </button>
    </div>
    
</div>