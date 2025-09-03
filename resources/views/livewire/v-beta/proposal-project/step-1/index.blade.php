<div x-show="currentStep === 1" class="space-y-6">
    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Informations Clés du Projet</h2>
    <p class="text-gray-600 dark:text-gray-300 mb-6">Renseignez les détails administratifs de base et choisissez le type de projet.</p>

    <div>
        <label for="selectedProjectTypeId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type de Projet <span class="text-red-500">*</span></label>
        <select id="selectedProjectTypeId" wire:model.live="selectedProjectTypeId"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
            <option value="">Sélectionner un type de projet</option>
            @foreach($allProjectTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
        @error('selectedProjectTypeId') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    @if ($selectedProjectTypeId)
        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-200 p-4 rounded-lg mb-6">
            <p class="font-semibold">Description du type de projet :</p>
            <p>{{ $allProjectTypes->where('id', $selectedProjectTypeId)->first()->description ?? 'N/A' }}</p>
        </div>
    @endif

    <div>
        <label for="projectTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Titre du Projet <span class="text-red-500">*</span></label>
        <input type="text" id="projectTitle" wire:model.defer="projectTitle"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Ex: Système de gestion de projet IA">
        @error('projectTitle') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="projectCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Code du Projet <span class="text-red-500">*</span></label>
        <input type="text" id="projectCode" wire:model.defer="projectCode"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Ex: PRJ-ALPHA-001">
        @error('projectCode') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="projectShortTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Titre Abrégé (Optionnel)</label>
        <input type="text" id="projectShortTitle" wire:model.defer="projectShortTitle"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Ex: SysGProj IA">
        @error('projectShortTitle') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="projectStartDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de Début <span class="text-red-500">*</span></label>
            <input type="date" id="projectStartDate" wire:model.defer="projectStartDate"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
            @error('projectStartDate') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="projectEndDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de Fin <span class="text-red-500">*</span></label>
            <input type="date" id="projectEndDate" wire:model.defer="projectEndDate"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
            @error('projectEndDate') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
    </div>
    
    {{-- Champs dynamiques pour cette section --}}
    @include('livewire.v-beta.proposal-project.dynamic-fields-section')
</div>