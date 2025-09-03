<main class="lg:ml-64 pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="p-6" wire:loading.class="opacity-50">

        {{-- Messages de session --}}
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-200 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-200 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                    @if ($projectId)
                        Éditer la Proposition de Projet
                    @else
                        Créer une Nouvelle Proposition de Projet
                    @endif
                </h1>
                
                <div 
                x-data="{
                    currentStep: @entangle('currentStep'),
                    totalSteps: @entangle('totalSteps'),
                    stepDetails: @entangle('stepDetails'),
                    updateProgress: function() {
                        const progress = (this.currentStep / this.totalSteps) * 100;
                        this.$refs.progressBarFill.style.width = `${progress}%`;
                    },
                    init() {
                        this.updateProgress();
                        this.$watch('currentStep', () => this.updateProgress());
                        Livewire.on('stepChanged', () => {
                            this.updateProgress();
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        });
                    }
                }" class="flex flex-col lg:flex-row gap-8">

                    {{-- Colonne de navigation des étapes --}}
                    <div class="lg:w-1/4 bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow-inner">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Étapes</h2>
                        <ul class="space-y-3">
                            <template x-for="(step, index) in stepDetails" :key="index">
                                <li class="flex items-center space-x-3 cursor-pointer p-2 rounded-md transition-colors"
                                    :class="{
                                        'bg-blue-600 text-white shadow-md': (index + 1) === currentStep,
                                        'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700': (index + 1) !== currentStep,
                                        'opacity-50 cursor-not-allowed': (index + 1) > currentStep // Désactiver les étapes futures
                                    }"
                                    @click="(index + 1) <= currentStep && $wire.goToStep(index + 1)">
                                    <span class="font-bold" x-text="index + 1"></span>
                                    <div>
                                        <p class="font-medium" x-text="step.title"></p>
                                        <p class="text-sm"
                                           :class="{'text-blue-200': (index + 1) === currentStep, 'text-gray-500 dark:text-gray-400': (index + 1) !== currentStep}"
                                           x-text="step.description"></p>
                                    </div>
                                </li>
                            </template>
                        </ul>

                        {{-- Barre de progression --}}
                        <div class="mt-8">
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Progression</div>
                            <div class="w-full bg-gray-300 dark:bg-gray-700 rounded-full h-2.5">
                                <div x-ref="progressBarFill" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300 ease-out" style="width: 0%;"></div>
                            </div>
                            <div class="text-right text-sm text-gray-600 dark:text-gray-400 mt-1">
                                <span x-text="currentStep"></span> / <span x-text="totalSteps"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Contenu du formulaire des étapes --}}
                    <form wire:submit.prevent="submitForm" class="lg:w-3/4 bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow-inner">
                        {{-- @csrf <!-- Indispensable pour la sécurité CSRF avec Livewire --> --}}

                        {{-- Fenêtre 1 : Informations Clés du Projet --}}
                        @include('livewire.v-beta.proposal-project.step-1.index')

                        {{-- Fenêtre 2 : Contexte & Documents --}}
                        @include('livewire.v-beta.proposal-project.step-2.index')

                        {{-- Fenêtre 3 : Cadre Logique (But & Objectifs Spécifiques) --}}
                        @include('livewire.v-beta.proposal-project.step-3.index')

                        {{-- Fenêtre 4 : Résultats Attendus --}}
                        @include('livewire.v-beta.proposal-project.step-4.index')

                        {{-- Fenêtre 5 : Activités Initiales --}}
                        @include('livewire.v-beta.proposal-project.step-5.index')

                        {{-- Fenêtre 6 : Budget Prévisionnel --}}
                        {{-- <div x-show="currentStep === 6" class="space-y-6">
                            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Budget Prévisionnel</h2>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">Estimez les coûts initiaux pour les principales lignes budgétaires du projet.</p>

                            <div class="space-y-4">
                                @foreach($budgets as $index => $budget)
                                    <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg relative">
                                        <button type="button" wire:click="removeBudget({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-lg">&times;</button>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <input type="hidden" wire:model="budgets.{{ $index }}.id">
                                                <label for="budget-description-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description de la Ligne Budgétaire <span class="text-red-500">*</span></label>
                                                <textarea id="budget-description-{{ $index }}" wire:model.defer="budgets.{{ $index }}.description" rows="2"
                                                          class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                                                          placeholder="Ex: Frais de personnel, Matériel de formation."></textarea>
                                                @error('budgets.' . $index . '.description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="budget-category-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catégorie (Optionnel)</label>
                                                <input type="text" id="budget-category-{{ $index }}" wire:model.defer="budgets.{{ $index }}.category"
                                                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                                                       placeholder="Ex: Ressources humaines, Logistique.">
                                                @error('budgets.' . $index . '.category') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="budget-quantity-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantité (Optionnel)</label>
                                                <input type="number" id="budget-quantity-{{ $index }}" wire:model.defer="budgets.{{ $index }}.quantity"
                                                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                                                @error('budgets.' . $index . '.quantity') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="budget-unit-cost-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Coût Unitaire (Optionnel)</label>
                                                <input type="number" step="0.01" id="budget-unit-cost-{{ $index }}" wire:model.defer="budgets.{{ $index }}.unit_cost"
                                                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                                                @error('budgets.' . $index . '.unit_cost') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="budget-total-cost-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Coût Total (Optionnel)</label>
                                                <input type="number" step="0.01" id="budget-total-cost-{{ $index }}" wire:model.defer="budgets.{{ $index }}.total_cost"
                                                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                                                @error('budgets.' . $index . '.total_cost') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="budget-responsible-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Responsable (Optionnel)</label>
                                                <select id="budget-responsible-{{ $index }}" wire:model.defer="budgets.{{ $index }}.responsible_user_id"
                                                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                                                    <option value="">Sélectionner un responsable</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('budgets.' . $index . '.responsible_user_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <button type="button" wire:click="addBudget" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold dark:bg-blue-600 dark:text-gray-100 dark:hover:bg-blue-700">
                                    Ajouter une Ligne Budgétaire
                                </button>
                            </div>
                            
                        </div> --}}

                        {{-- Fenêtre 7 : Finalisation --}}
                        @include('livewire.v-beta.proposal-project.step-final.index')

                        {{-- Boutons de navigation --}}
                        <div class="flex justify-between items-center mt-8">
                            @if ($currentStep > 1)
                                <button type="button" wire:click="previousStep" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-semibold">
                                    Précédent
                                </button>
                            @else
                                <div></div>
                            @endif

                            @if ($currentStep < $totalSteps)
                                <button type="button" wire:click="nextStep" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold dark:bg-blue-600 dark:text-gray-100 dark:hover:bg-blue-700">
                                    Suivant
                                </button>
                            @else
                                <button type="submit" class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-semibold dark:bg-emerald-600 dark:text-gray-100 dark:hover:bg-emerald-700" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="submitForm">Soumettre la Proposition</span>
                                    <span wire:loading wire:target="submitForm">Soumission...</span>
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
