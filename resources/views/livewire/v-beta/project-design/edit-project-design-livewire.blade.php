<main class="lg:ml-64 pt-16 min-h-screen bg-surface">
    <div class="p-6" wire:loading.class="opacity-50">

        {{-- Messages de session --}}
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-success/5 border border-success/20 text-success rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-error/5 border border-error/20 text-error rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-card overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-card border-b border-border">
                <h1 class="text-3xl font-bold text-heading mb-6">{{ __('projects.design.edit_title') }} : <span class="text-accent">{{ $projectTitle }}</span></h1>

                {{-- Wizard Navigation --}}
                <div x-data="{ currentStep: @entangle('currentStep'), totalSteps: @entangle('totalSteps'), stepDetails: @entangle('stepDetails'), updateProgress: function() { const progress = (this.currentStep / this.totalSteps) * 100; this.$refs.progressBarFill.style.width = `${progress}%`; }, init() { this.updateProgress(); this.$watch('currentStep', () => this.updateProgress()); Livewire.on('stepChanged', () => { this.updateProgress(); window.scrollTo({ top: 0, behavior: 'smooth' }); }); Livewire.on('showAlert', (data) => { Swal.fire({ title: data.title, text: data.text, icon: data.icon, confirmButtonText: data.confirmButtonText }); }); } }" class="flex flex-col lg:flex-row gap-8">
                    {{-- Colonne de navigation des étapes --}}
                    <div class="lg:w-1/4 bg-surface-alt p-6 rounded-lg shadow-inner">
                        <h2 class="text-xl font-semibold text-heading mb-4">{{ __('projects.design.steps') }}</h2>
                        <ul class="space-y-3">
                            <template x-for="(step, index) in stepDetails" :key="index">
                                <li class="flex items-center space-x-3 cursor-pointer p-2 rounded-md transition-colors"
                                    :class="{ 'bg-accent text-white shadow-md': (index + 1) === currentStep, 'text-body hover:bg-surface-alt': (index + 1) !== currentStep, 'opacity-50 cursor-not-allowed': (index + 1) > currentStep }"
                                    @click="(index + 1) <= currentStep && $wire.goToStep(index + 1)">
                                    <span class="font-bold" x-text="index + 1"></span>
                                    <div>
                                        <p class="font-medium" x-text="step.title"></p>
                                        <p class="text-sm" :class="{'text-white/70': (index + 1) === currentStep, 'text-subtle': (index + 1) !== currentStep}" x-text="step.description"></p>
                                    </div>
                                </li>
                            </template>
                        </ul>
                        {{-- Barre de progression --}}
                        <div class="mt-8">
                            <div class="text-sm font-medium text-body mb-1">
                                {{ __('projects.design.progress') }}
                            </div>
                            <div class="w-full bg-border rounded-full h-2.5">
                                <div x-ref="progressBarFill" class="bg-accent h-2.5 rounded-full transition-all duration-500 ease-out" style="width: 0%;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Contenu des étapes --}}
                    <div class="lg:w-3/4">
                        <form wire:submit.prevent="submit">
                            {{-- Étape 1: Informations Clés --}}
                            @if ($currentStep == 1)
                                <div class="bg-card rounded-lg shadow-md p-6">
                                    <h2 class="text-2xl font-semibold text-heading mb-4">{{ __('projects.design.key_info') }}</h2>
                                    <p class="text-subtle mb-6">Mettez à jour les détails de base de votre projet.</p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        {{-- Titre du Projet --}}
                                        <div>
                                            <label for="projectTitle" class="block text-sm font-medium text-body">{{ __('projects.design.project_title') }}</label>
                                            <input type="text" id="projectTitle" wire:model.defer="projectTitle" class="form-input mt-1 block w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading">
                                            @error('projectTitle') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Code du Projet --}}
                                        <div>
                                            <label for="projectCode" class="block text-sm font-medium text-body">{{ __('projects.design.project_code') }}</label>
                                            <input type="text" id="projectCode" wire:model.defer="projectCode" class="form-input mt-1 block w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading">
                                            @error('projectCode') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Date de Début --}}
                                        <div>
                                            <label for="projectStartDate" class="block text-sm font-medium text-body">{{ __('projects.design.start_date') }}</label>
                                            <input type="date" id="projectStartDate" wire:model.defer="projectStartDate" class="form-input mt-1 block w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading">
                                            @error('projectStartDate') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Date de Fin --}}
                                        <div>
                                            <label for="projectEndDate" class="block text-sm font-medium text-body">{{ __('projects.design.end_date') }}</label>
                                            <input type="date" id="projectEndDate" wire:model.defer="projectEndDate" class="form-input mt-1 block w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading">
                                            @error('projectEndDate') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="mt-6">
                                        <label for="projectDescriptionGeneral" class="block text-sm font-medium text-body">{{ __('projects.design.general_description') }}</label>
                                        <textarea id="projectDescriptionGeneral" wire:model.defer="projectDescriptionGeneral" rows="4" class="form-textarea mt-1 block w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading"></textarea>
                                        @error('projectDescriptionGeneral') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-6">
                                        <label for="problemAnalysis" class="block text-sm font-medium text-body">{{ __('projects.show.problem_analysis') }}</label>
                                        <textarea id="problemAnalysis" wire:model.defer="problemAnalysis" rows="4" class="form-textarea mt-1 block w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading"></textarea>
                                        @error('problemAnalysis') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-6">
                                        <label for="strategy" class="block text-sm font-medium text-body">{{ __('projects.show.strategy') }}</label>
                                        <textarea id="strategy" wire:model.defer="strategy" rows="4" class="form-textarea mt-1 block w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading"></textarea>
                                        @error('strategy') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-6">
                                        <label for="justification" class="block text-sm font-medium text-body">{{ __('projects.show.justification') }}</label>
                                        <textarea id="justification" wire:model.defer="justification" rows="4" class="form-textarea mt-1 block w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading"></textarea>
                                        @error('justification') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- Étape 2: Contexte --}}
                            @if ($currentStep == 2)
                                <div class="bg-card rounded-lg shadow-md p-6">
                                    <h2 class="text-2xl font-semibold text-heading mb-4">Contexte du Projet</h2>
                                    <p class="text-subtle mb-6">Veuillez fournir une description simple pour assister la génération par l'IA.</p>
                                    <div class="mt-6">
                                        <label for="baseProjectDescription" class="block text-sm font-medium text-body">Description simplifiée</label>
                                        <textarea id="baseProjectDescription" wire:model.defer="baseProjectDescription" rows="4" class="form-textarea mt-1 block w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading"></textarea>
                                        @error('baseProjectDescription') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- Étape 3: Documents --}}
                            @if ($currentStep == 3)
                                <div class="bg-card rounded-lg shadow-md p-6">
                                    <h2 class="text-2xl font-semibold text-heading mb-4">Documents Associés</h2>
                                    <p class="text-subtle mb-6">Gérez les documents existants et ajoutez de nouveaux fichiers.</p>

                                    {{-- Documents existants --}}
                                    @if (!empty($existingDocuments))
                                        <h3 class="text-lg font-medium text-heading mb-2">Documents Actuels</h3>
                                        <ul class="list-disc list-inside space-y-2 mb-4">
                                            @foreach ($existingDocuments as $document)
                                                <li class="flex items-center justify-between text-body">
                                                    <span>{{ $document['file_name'] }}</span>
                                                    <button type="button" wire:click="removeExistingDocument('{{ $document['id'] }}')" class="text-error hover:text-error-dark text-sm">{{ __('common.delete') }}</button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    {{-- Nouveau upload --}}
                                    <div>
                                        <label for="uploadedDocuments" class="block text-sm font-medium text-body">Ajouter de nouveaux documents</label>
                                        <input type="file" id="uploadedDocuments" wire:model="uploadedDocuments" multiple class="mt-1 block w-full text-sm text-subtle file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-accent/10 file:text-accent hover:file:bg-accent/20">
                                        <div wire:loading wire:target="uploadedDocuments" class="text-sm text-accent mt-2">Chargement des fichiers...</div>
                                        @error('uploadedDocuments.*') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- Étape 4: Cadre Logique (But, Objectifs, Résultats et Activités) --}}
                            @if ($currentStep == 4)
                                <div class="bg-card rounded-lg shadow-md p-6">
                                    <h2 class="text-2xl font-semibold text-heading mb-4">Cadre Logique</h2>

                                    {{-- But Général --}}
                                    <div class="mb-6">
                                        <label for="generalGoal" class="block text-sm font-medium text-body">But Général</label>
                                        <input type="text" id="generalGoal" wire:model.defer="generalGoal" class="form-input mt-1 block w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading">
                                        @error('generalGoal') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Indicateurs du But Général --}}
                                    <div class="mb-6 ml-4 border-l-2 border-success/40 pl-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-sm font-medium text-body">Indicateurs du But Général</h4>
                                            <button type="button" wire:click="addIndicator('logframe')" class="text-xs text-success hover:text-success-dark font-semibold">+ Ajouter un indicateur</button>
                                        </div>
                                        @forelse ($logframeIndicators as $iIdx => $indicator)
                                            <div wire:key="lf-ind-{{ $iIdx }}" class="bg-surface dark:bg-surface-alt/50 rounded-md p-3 mb-2 relative">
                                                <button type="button" wire:click="removeIndicator('logframe', null, null, {{ $iIdx }})" class="absolute top-1 right-1 text-error/70 hover:text-error">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                                <div class="pr-6 space-y-2">
                                                    <input type="text" wire:model.defer="logframeIndicators.{{ $iIdx }}.description" placeholder="Description de l'indicateur" class="form-input w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading text-sm">
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <input type="text" wire:model.defer="logframeIndicators.{{ $iIdx }}.verification_source" placeholder="Source de vérification" class="form-input w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading text-sm">
                                                        <input type="text" wire:model.defer="logframeIndicators.{{ $iIdx }}.assumption" placeholder="Hypothèse" class="form-input w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading text-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-xs text-muted italic">Aucun indicateur. Cliquez sur "Ajouter" ci-dessus.</p>
                                        @endforelse
                                    </div>

                                    {{-- Objectifs Spécifiques et Résultats --}}
                                    <h3 class="text-lg font-medium text-heading mb-2">Objectifs Spécifiques</h3>
                                    @foreach ($specificObjectives as $objIndex => $objective)
                                        <div class="border-l-4 border-accent pl-4 mb-6">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <input type="text" wire:model.defer="specificObjectives.{{ $objIndex }}.description" placeholder="Description de l'objectif spécifique" class="form-input flex-grow rounded-md shadow-sm dark:bg-surface-alt dark:text-heading">
                                                <button type="button" wire:click="removeObjective({{ $objIndex }})" class="text-error hover:text-error-dark">
                                                    <x-lucide-trash-2 class="h-5 w-5" />
                                                </button>
                                            </div>
                                            @error("specificObjectives.$objIndex.description") <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror

                                            {{-- Indicateurs de l'objectif --}}
                                            <div class="ml-4 border-l-2 border-success/40 pl-3 mt-3 mb-4">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h5 class="text-xs font-medium text-subtle">Indicateurs</h5>
                                                    <button type="button" wire:click="addIndicator('objective', {{ $objIndex }})" class="text-xs text-success hover:text-success-dark font-semibold">+ Ajouter</button>
                                                </div>
                                                @forelse ($objective['indicators_list'] ?? [] as $iIdx => $indicator)
                                                    <div wire:key="obj-{{ $objIndex }}-ind-{{ $iIdx }}" class="bg-surface dark:bg-surface-alt/50 rounded-md p-3 mb-2 relative">
                                                        <button type="button" wire:click="removeIndicator('objective', {{ $objIndex }}, null, {{ $iIdx }})" class="absolute top-1 right-1 text-error/70 hover:text-error">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                        <div class="pr-6 space-y-2">
                                                            <input type="text" wire:model.defer="specificObjectives.{{ $objIndex }}.indicators_list.{{ $iIdx }}.description" placeholder="Description de l'indicateur" class="form-input w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading text-sm">
                                                            <div class="grid grid-cols-2 gap-2">
                                                                <input type="text" wire:model.defer="specificObjectives.{{ $objIndex }}.indicators_list.{{ $iIdx }}.verification_source" placeholder="Source de vérification" class="form-input w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading text-sm">
                                                                <input type="text" wire:model.defer="specificObjectives.{{ $objIndex }}.indicators_list.{{ $iIdx }}.assumption" placeholder="Hypothèse" class="form-input w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading text-sm">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-xs text-muted italic">Aucun indicateur.</p>
                                                @endforelse
                                            </div>

                                            {{-- Résultats --}}
                                            <h4 class="text-md font-medium text-body mt-4 mb-2">Résultats</h4>
                                            @foreach ($objective['results'] as $resIndex => $result)
                                                <div class="border-l-2 border-muted pl-3 mb-4">
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <input type="text" wire:model.defer="specificObjectives.{{ $objIndex }}.results.{{ $resIndex }}.description" placeholder="Description du résultat" class="form-input flex-grow rounded-md shadow-sm dark:bg-surface-alt dark:text-heading">
                                                        <button type="button" wire:click="removeResult({{ $objIndex }}, {{ $resIndex }})" class="text-error hover:text-error-dark">
                                                            <x-lucide-trash-2 class="h-4 w-4" />
                                                        </button>
                                                    </div>

                                                    {{-- Indicateurs du résultat --}}
                                                    <div class="ml-4 border-l-2 border-success/40 pl-3 mt-2 mb-3">
                                                        <div class="flex items-center justify-between mb-2">
                                                            <h6 class="text-xs font-medium text-subtle">Indicateurs</h6>
                                                            <button type="button" wire:click="addIndicator('result', {{ $objIndex }}, {{ $resIndex }})" class="text-xs text-success hover:text-success-dark font-semibold">+ Ajouter</button>
                                                        </div>
                                                        @forelse ($result['indicators_list'] ?? [] as $iIdx => $indicator)
                                                            <div wire:key="res-{{ $objIndex }}-{{ $resIndex }}-ind-{{ $iIdx }}" class="bg-surface dark:bg-surface-alt/50 rounded-md p-3 mb-2 relative">
                                                                <button type="button" wire:click="removeIndicator('result', {{ $objIndex }}, {{ $resIndex }}, {{ $iIdx }})" class="absolute top-1 right-1 text-error/70 hover:text-error">
                                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                </button>
                                                                <div class="pr-6 space-y-2">
                                                                    <input type="text" wire:model.defer="specificObjectives.{{ $objIndex }}.results.{{ $resIndex }}.indicators_list.{{ $iIdx }}.description" placeholder="Description de l'indicateur" class="form-input w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading text-sm">
                                                                    <div class="grid grid-cols-2 gap-2">
                                                                        <input type="text" wire:model.defer="specificObjectives.{{ $objIndex }}.results.{{ $resIndex }}.indicators_list.{{ $iIdx }}.verification_source" placeholder="Source de vérification" class="form-input w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading text-sm">
                                                                        <input type="text" wire:model.defer="specificObjectives.{{ $objIndex }}.results.{{ $resIndex }}.indicators_list.{{ $iIdx }}.assumption" placeholder="Hypothèse" class="form-input w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading text-sm">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <p class="text-xs text-muted italic">Aucun indicateur.</p>
                                                        @endforelse
                                                    </div>

                                                    {{-- Activités imbriquées --}}
                                                    <h5 class="text-sm font-medium text-subtle mt-4 mb-2">Activités</h5>
                                                    @foreach ($result['activities'] as $actIndex => $activity)
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <div class="flex-grow">
                                                                <input type="text" wire:model.defer="specificObjectives.{{ $objIndex }}.results.{{ $resIndex }}.activities.{{ $actIndex }}.description" placeholder="Description de l'activité" class="form-input w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading text-sm">
                                                            </div>
                                                            <div class="w-1/3">
                                                                <select wire:model.defer="specificObjectives.{{ $objIndex }}.results.{{ $resIndex }}.activities.{{ $actIndex }}.responsible" class="form-select w-full rounded-md shadow-sm dark:bg-surface-alt dark:text-heading text-sm">
                                                                    <option value="">{{ __('common.responsible') }}</option>
                                                                    @foreach ($users as $user)
                                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <button type="button" wire:click="removeActivity({{ $objIndex }}, {{ $resIndex }}, {{ $actIndex }})" class="text-error hover:text-error-dark">
                                                                <x-lucide-trash-2 class="h-4 w-4" />
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                    <button type="button" wire:click="addActivity({{ $objIndex }}, {{ $resIndex }})" class="mt-2 text-xs text-accent hover:text-accent-dark">
                                                        + Ajouter une activité
                                                    </button>
                                                </div>
                                            @endforeach
                                            <button type="button" wire:click="addResult({{ $objIndex }})" class="mt-2 text-sm text-accent hover:text-accent-dark">
                                                + Ajouter un résultat
                                            </button>
                                        </div>
                                    @endforeach
                                    <button type="button" wire:click="addObjective" class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent-dark transition-colors font-semibold">
                                        Ajouter un objectif spécifique
                                    </button>
                                </div>
                            @endif

                            {{-- Boutons de navigation --}}
                            <div class="flex justify-between items-center mt-8">
                                @if ($currentStep > 1)
                                    <button type="button" wire:click="previousStep" class="px-6 py-3 bg-surface-alt text-heading rounded-lg hover:bg-border transition-colors font-semibold">
                                        {{ __('common.previous') }}
                                    </button>
                                @else
                                    <div></div>
                                @endif

                                @if ($currentStep < $totalSteps)
                                    <button type="button" wire:click="nextStep" class="px-6 py-3 bg-accent text-white rounded-lg hover:bg-accent-dark transition-colors font-semibold">
                                        {{ __('common.next') }}
                                    </button>
                                @else
                                    <button type="submit" class="px-6 py-3 bg-success text-white rounded-lg hover:bg-success-dark transition-colors font-semibold">
                                        {{ __('common.save') }}
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>