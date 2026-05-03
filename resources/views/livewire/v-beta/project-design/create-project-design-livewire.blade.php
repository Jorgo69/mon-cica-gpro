<main class="lg:ml-64 pt-16 min-h-screen bg-surface">
    <div class="p-6" wire:loading.class="opacity-50">

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
                <h1 class="text-3xl font-bold text-heading mb-6">{{ __('projects.design.create_title') }}</h1>
                {{-- Conteneur principal du formulaire avec Alpine.js et Livewire --}}
                {{-- RETRAIT DE wire:ignore.self POUR PERMETTRE LA MISE À JOUR DU FORMULAIRE --}}
                
                <div x-data="{
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
                            // Optional: scroll to top on step change for better UX
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        });
                        // Initialize Swal for messages
                        Livewire.on('showAlert', (data) => {
                            Swal.fire({
                                title: data.title,
                                text: data.text,
                                icon: data.icon,
                                confirmButtonText: data.confirmButtonText
                            });
                        });
                    }
                }" class="flex flex-col lg:flex-row gap-8">

                    {{-- Colonne de navigation des étapes --}}
                    <div class="lg:w-1/4 bg-surface-alt p-6 rounded-lg shadow-inner">
                        <h2 class="text-xl font-semibold text-heading mb-4">{{ __('projects.design.steps') }}</h2>
                        <ul class="space-y-3">
                            <template x-for="(step, index) in stepDetails" :key="index">
                                <li class="flex items-center space-x-3 cursor-pointer p-2 rounded-md transition-colors"
                                    :class="{
                                        'bg-accent text-white shadow-md': (index + 1) === currentStep,
                                        'text-body hover:bg-surface-alt': (index + 1) !== currentStep,
                                        'opacity-50 cursor-not-allowed': (index + 1) > currentStep // Disable future steps
                                    }"
                                    @click="(index + 1) <= currentStep && $wire.goToStep(index + 1)">
                                    <span class="font-bold" x-text="index + 1"></span>
                                    <div>
                                        <p class="font-medium" x-text="step.title"></p>
                                        <p class="text-sm"
                                           :class="{'text-white/70': (index + 1) === currentStep, 'text-subtle': (index + 1) !== currentStep}"
                                           x-text="step.description"></p>
                                    </div>
                                </li>
                            </template>
                        </ul>

                        {{-- Barre de progression --}}
                        <div class="mt-8">
                            <div class="text-sm font-medium text-body mb-2">{{ __('projects.design.progress') }}</div>
                            <div class="w-full bg-border rounded-full h-2.5">
                                <div x-ref="progressBarFill" class="bg-accent h-2.5 rounded-full transition-all duration-300 ease-out" style="width: 0%;"></div>
                            </div>
                            <div class="text-right text-sm text-subtle mt-1">
                                <span x-text="currentStep"></span> / <span x-text="totalSteps"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Contenu du formulaire des étapes --}}
                    <form wire:submit.prevent="submitForm" class="lg:w-3/4 bg-surface-alt p-6 rounded-lg shadow-inner">

                        {{-- Fenêtre 1 : Informations Générales & Contexte IA --}}
                        <div x-show="currentStep === 1" class="space-y-6">
                            <h2 class="text-2xl font-semibold text-heading mb-4">{{ __('projects.design.key_info') }}</h2>
                            <p class="text-body mb-6">{{ __('projects.design.key_info_desc') }}</p>

                            <div class="bg-info/5 border border-info/20 text-info p-4 rounded-lg mb-6">
                                <p class="font-semibold">{{ __('projects.design.ai_tip') }} :</p>
                                <p>{{ __('projects.design.ai_tip_desc') }}</p>
                            </div>

                            <div>
                                <label for="projectTitle" class="block text-sm font-medium text-body">{{ __('projects.design.project_title') }} <span class="text-error">*</span></label>
                                <input type="text" id="projectTitle" wire:model.defer="projectTitle"
                                       class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                       placeholder="Ex: Système de gestion de projet IA">
                                @error('projectTitle') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="projectCode" class="block text-sm font-medium text-body">{{ __('projects.design.project_code') }} <span class="text-error">*</span></label>
                                <input type="text" id="projectCode" wire:model.defer="projectCode"
                                       class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                       placeholder="Ex: PRJ-ALPHA-001">
                                @error('projectCode') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="projectShortTitle" class="block text-sm font-medium text-body">{{ __('projects.design.short_title') }}</label>
                                <input type="text" id="projectShortTitle" wire:model.defer="projectShortTitle"
                                       class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                       placeholder="Ex: SysGProj IA">
                                @error('projectShortTitle') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="projectDescriptionGeneral" class="block text-sm font-medium text-body">{{ __('projects.design.general_description') }}</label>
                                <textarea id="projectDescriptionGeneral" wire:model.defer="projectDescriptionGeneral" rows="3"
                                          class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                          placeholder="Fournissez un aperçu général du projet."></textarea>
                                @error('projectDescriptionGeneral') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="projectStartDate" class="block text-sm font-medium text-body">{{ __('projects.design.start_date') }} <span class="text-error">*</span></label>
                                    <input type="date" id="projectStartDate" wire:model.defer="projectStartDate"
                                           class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent">
                                    @error('projectStartDate') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="projectEndDate" class="block text-sm font-medium text-body">{{ __('projects.design.end_date') }} <span class="text-error">*</span></label>
                                    <input type="date" id="projectEndDate" wire:model.defer="projectEndDate"
                                           class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent">
                                    @error('projectEndDate') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="projectStatus" class="block text-sm font-medium text-body">{{ __('projects.design.project_status') }} <span class="text-error">*</span></label>
                                <select id="projectStatus" wire:model.defer="projectStatus"
                                        class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent">
                                    <option value="draft">{{ __('common.draft') }}</option>
                                    <option value="active">{{ __('common.active') }}</option>
                                    <option value="completed">{{ __('common.completed') }}</option>
                                    <option value="on_hold">{{ __('common.pending') }}</option>
                                    <option value="cancelled">{{ __('common.cancelled') }}</option>
                                </select>
                                @error('projectStatus') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="baseProjectDescription" class="block text-sm font-medium text-body">Description de Base pour l'IA <span class="text-error">*</span></label>
                                <textarea id="baseProjectDescription" wire:model.defer="baseProjectDescription" rows="5"
                                          class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                          placeholder="Décrivez le problème que le projet vise à résoudre, le public cible, et les résultats souhaités pour guider l'IA."></textarea>
                                @error('baseProjectDescription') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="uploadedDocuments" class="block text-sm font-medium text-body">Documents Pertinents (Optionnel)</label>
                                <input type="file" id="uploadedDocuments" wire:model="uploadedDocuments" multiple
                                       class="mt-1 block w-full text-sm text-heading file:mr-4 file:py-2 file:px-4
                                              file:rounded-full file:border-0 file:text-sm file:font-semibold
                                              file:bg-accent/10 file:text-accent hover:file:bg-accent/20">
                                <p class="mt-1 text-sm text-subtle">Formats acceptés : PDF, DOCX, XLSX, JPG, PNG. Taille max : 5MB par fichier.</p>
                                @error('uploadedDocuments.*') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                @if (count($uploadedDocuments) > 0)
                                    <div class="mt-2 text-sm text-body">
                                        Fichiers sélectionnés:
                                        <ul class="list-disc list-inside">
                                            @foreach ($uploadedDocuments as $file)
                                                <li>{{ $file->getClientOriginalName() }} ({{ round($file->getSize() / 1024 / 1024, 2) }} MB)</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Fenêtre 2 : Analyse Initiale de l'Environnement --}}
                        <div x-show="currentStep === 2" x-cloak class="space-y-6">
                            <h2 class="text-2xl font-semibold text-heading mb-4">Contexte Environnemental du Projet</h2>
                            <p class="text-body mb-6">Évaluez les facteurs externes et internes qui peuvent influencer votre projet (PESTEL, SWOT).</p>

                            <div class="bg-info/5 border border-info/20 text-info p-4 rounded-lg mb-6">
                                <p class="font-semibold">Conseil IA :</p>
                                <p>Une analyse PESTEL et SWOT approfondie permet à l'IA d'identifier les risques et opportunités cachés, améliorant la robustesse de votre conception.</p>
                            </div>

                            <div>
                                <label for="environmentAnalysisText" class="block text-sm font-medium text-body">Analyse de l'environnement (PESTEL, SWOT) <span class="text-error">*</span></label>
                                <textarea id="environmentAnalysisText" wire:model.defer="environmentAnalysisText" rows="10"
                                          class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                          placeholder="Décrivez les facteurs Politiques, Économiques, Socio-culturels, Technologiques, Écologiques, Légaux (PESTEL) et les Forces, Faiblesses, Opportunités, Menaces (SWOT) du projet."></textarea>
                                @error('environmentAnalysisText') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Fenêtre 3 : Acteurs Clés du Projet --}}
                        <div x-show="currentStep === 3" x-cloak class="space-y-6">
                            <h2 class="text-2xl font-semibold text-heading mb-4">Identification des Parties Prenantes</h2>
                            <p class="text-body mb-6">Listez les individus ou groupes qui seront affectés par le projet, ou qui l'affecteront.</p>

                            <div class="bg-info/5 border border-info/20 text-info p-4 rounded-lg mb-6">
                                <p class="font-semibold">Conseil IA :</p>
                                <p>Comprendre les attentes et les impacts sur chaque partie prenante aide l'IA à suggérer des stratégies d'engagement efficaces et des solutions adaptées.</p>
                            </div>

                            <div class="space-y-4">
                                @if(isset($stakeholders) && is_array($stakeholders))
                                    @foreach($stakeholders as $index => $stakeholder)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 border border-border rounded-lg relative">
                                            <div>
                                                <label for="stakeholder-name-{{ $index }}" class="block text-sm font-medium text-body">Nom <span class="text-error">*</span></label>
                                                <input type="text" id="stakeholder-name-{{ $index }}" wire:model.defer="stakeholders.{{ $index }}.name"
                                                       class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                                       placeholder="Nom de la partie prenante">
                                                @error('stakeholders.' . $index . '.name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="stakeholder-role-{{ $index }}" class="block text-sm font-medium text-body">Rôle <span class="text-error">*</span></label>
                                                <input type="text" id="stakeholder-role-{{ $index }}" wire:model.defer="stakeholders.{{ $index }}.role"
                                                       class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                                       placeholder="Ex: Bénéficiaire, Financeur, Décideur">
                                                @error('stakeholders.' . $index . '.role') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <button type="button" wire:click="removeStakeholder({{ $index }})"
                                                    class="absolute top-2 right-2 text-error hover:text-error-dark text-lg">&times;</button>
                                        </div>
                                    @endforeach
                                @endif
                                <button type="button" wire:click="addStakeholder" class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent-dark transition-colors font-semibold">
                                    Ajouter une Partie Prenante
                                </button>
                                @error('stakeholders') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Fenêtre 4 : Problématique Cible --}}
                        <div x-show="currentStep === 4" x-cloak class="space-y-6">
                            <h2 class="text-2xl font-semibold text-heading mb-4">Analyse Détaillée du Problème</h2>
                            <p class="text-body mb-6">Décrivez précisément le problème que votre projet vise à résoudre, en étayant avec des faits ou des données.</p>

                            <div class="bg-info/5 border border-info/20 text-info p-4 rounded-lg mb-6">
                                <p class="font-semibold">Conseil IA :</p>
                                <p>Une définition claire et factuelle du problème est la fondation d'une solution innovante. L'IA utilisera cette analyse pour cibler ses recommandations.</p>
                            </div>

                            <div>
                                <label for="problemAnalysisText" class="block text-sm font-medium text-body">Analyse Détaillée du Problème <span class="text-error">*</span></label>
                                <textarea id="problemAnalysisText" wire:model.defer="problemAnalysisText" rows="10"
                                          class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                          placeholder="Ex: La faible adoption des services numériques par les petites entreprises locales en raison d'un manque de formation et d'infrastructures adéquates, entraînant une stagnation économique."></textarea>
                                @error('problemAnalysisText') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Fenêtre 5 : Stratégie & Approche --}}
                        <div x-show="currentStep === 5" x-cloak class="space-y-6">
                            <h2 class="text-2xl font-semibold text-heading mb-4">Définition de la Stratégie</h2>
                            <p class="text-body mb-6">Élaborez l'approche globale que votre projet adoptera pour atteindre ses objectifs.</p>

                            <div class="bg-info/5 border border-info/20 text-info p-4 rounded-lg mb-6">
                                <p class="font-semibold">Conseil IA :</p>
                                <p>Une stratégie bien articulée fournit à l'IA un cadre pour aligner les objectifs, les résultats et les activités, garantissant la cohérence du projet.</p>
                            </div>

                            <div>
                                <label for="strategyDefinitionText" class="block text-sm font-medium text-body">Définition de la Stratégie Globale du Projet <span class="text-error">*</span></label>
                                <textarea id="strategyDefinitionText" wire:model.defer="strategyDefinitionText" rows="10"
                                          class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                          placeholder="Décrivez l'approche générale du projet, la méthodologie choisie, et les principes directeurs."></textarea>
                                @error('strategyDefinitionText') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Fenêtre 6 : But Général & Objectifs Spécifiques --}}
                        <div x-show="currentStep === 6" x-cloak class="space-y-6">
                            <h2 class="text-2xl font-semibold text-heading mb-4">Buts et Objectifs du Projet</h2>
                            <p class="text-body mb-6">Énoncez le but global et les objectifs spécifiques (SMART) qui guideront votre projet.</p>

                            <div class="bg-info/5 border border-info/20 text-info p-4 rounded-lg mb-6">
                                <p class="font-semibold">Conseil IA :</p>
                                <p>Des objectifs SMART (Spécifiques, Mesurables, Atteignables, Réalistes, Temporellement définis) permettent à l'IA de générer des résultats et des activités hautement pertinents et vérifiables.</p>
                            </div>

                            <div>
                                <label for="generalGoal" class="block text-sm font-medium text-body">But Général du Projet <span class="text-error">*</span></label>
                                <textarea id="generalGoal" wire:model.defer="generalGoal" rows="3"
                                          class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                          placeholder="Ex: Contribuer à l'amélioration de la digitalisation des PME locales."></textarea>
                                @error('generalGoal') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <h3 class="text-xl font-semibold text-heading mt-6 mb-3">Objectifs Spécifiques</h3>
                            <div class="space-y-4">
                                @if(isset($specificObjectives) && is_array($specificObjectives))
                                    @foreach($specificObjectives as $index => $objective)
                                        <div class="mb-4 p-4 border border-border rounded-lg relative">
                                            <div>
                                                <label for="objective-description-{{ $index }}" class="block text-sm font-medium text-body">Description de l'Objectif <span class="text-error">*</span></label>
                                                <textarea id="objective-description-{{ $index }}" wire:model.defer="specificObjectives.{{ $index }}.description" rows="2"
                                                          class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                                          placeholder="Ex: Augmenter le nombre de PME utilisant des outils numériques de 20% en 12 mois."></textarea>
                                                @error('specificObjectives.' . $index . '.description') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <button type="button" wire:click="removeSpecificObjective({{ $index }})"
                                                    class="absolute top-2 right-2 text-error hover:text-error-dark text-lg">&times;</button>
                                        </div>
                                    @endforeach
                                @endif
                                <button type="button" wire:click="addSpecificObjective" class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent-dark transition-colors font-semibold">
                                    Ajouter un Objectif Spécifique
                                </button>
                                @error('specificObjectives') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Fenêtre 7 : Résultats Concrets & Mesures --}}
                        <div x-show="currentStep === 7" x-cloak class="space-y-6">
                            <h2 class="text-2xl font-semibold text-heading mb-4">Résultats Attendus et Indicateurs</h2>
                            <p class="text-body mb-6">Définissez les livrables concrets et les indicateurs qui prouveront que les résultats ont été atteints.</p>

                            <div class="bg-info/5 border border-info/20 text-info p-4 rounded-lg mb-6">
                                <p class="font-semibold">Conseil IA :</p>
                                <p>Des résultats bien définis avec des indicateurs clairs sont essentiels pour le suivi de la performance. L'IA peut vous aider à affiner ces mesures pour une évaluation efficace.</p>
                            </div>

                            <h3 class="text-xl font-semibold text-heading mt-6 mb-3">Résultats Attendus</h3>
                            <div class="space-y-4">
                                @if(isset($expectedResults) && is_array($expectedResults))
                                    @foreach($expectedResults as $index => $result)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 border border-border rounded-lg relative">
                                            <div>
                                                <label for="result-description-{{ $index }}" class="block text-sm font-medium text-body">Description du Résultat <span class="text-error">*</span></label>
                                                <textarea id="result-description-{{ $index }}" wire:model.defer="expectedResults.{{ $index }}.description" rows="2"
                                                          class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                                          placeholder="Ex: 50 PME formées à l'utilisation d'outils de gestion en ligne."></textarea>
                                                @error('expectedResults.' . $index . '.description') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="result-indicators-{{ $index }}" class="block text-sm font-medium text-body">Indicateurs de Vérification <span class="text-error">*</span></label>
                                                <textarea id="result-indicators-{{ $index }}" wire:model.defer="expectedResults.{{ $index }}.indicators" rows="2"
                                                          class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                                          placeholder="Ex: Liste de présence aux formations, rapports d'utilisation des outils."></textarea>
                                                @error('expectedResults.' . $index . '.indicators') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <button type="button" wire:click="removeExpectedResult({{ $index }})"
                                                    class="absolute top-2 right-2 text-error hover:text-error-dark text-lg">&times;</button>
                                        </div>
                                    @endforeach
                                @endif
                                <button type="button" wire:click="addExpectedResult" class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent-dark transition-colors font-semibold">
                                    Ajouter un Résultat Attendu
                                </button>
                                @error('expectedResults') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Fenêtre 8 : Plan d'Action --}}
                        <div x-show="currentStep === 8" x-cloak class="space-y-6">
                            <h2 class="text-2xl font-semibold text-heading mb-4">Activités du Projet</h2>
                            <p class="text-body mb-6">Listez les actions principales nécessaires pour réaliser les résultats attendus.</p>

                            <div class="bg-info/5 border border-info/20 text-info p-4 rounded-lg mb-6">
                                <p class="font-semibold">Conseil IA :</p>
                                <p>La décomposition du projet en activités claires permet à l'IA d'organiser le plan de travail et de suggérer des responsabilités adaptées.</p>
                            </div>

                            <h3 class="text-xl font-semibold text-heading mt-6 mb-3">Activités Principales</h3>
                            <div class="space-y-4">
                                @if(isset($activities) && is_array($activities))
                                    @foreach($activities as $index => $activity)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 border border-border rounded-lg relative">
                                            <div>
                                                <label for="activity-description-{{ $index }}" class="block text-sm font-medium text-body">Description de l'Activité <span class="text-error">*</span></label>
                                                <textarea id="activity-description-{{ $index }}" wire:model.defer="activities.{{ $index }}.description" rows="2"
                                                          class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                                          placeholder="Ex: Organiser des ateliers de formation sur l'utilisation de CRM."></textarea>
                                                @error('activities.' . $index . '.description') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="activity-responsible-{{ $index }}" class="block text-sm font-medium text-body">Responsable <span class="text-error">*</span></label>
                                                <input type="text" id="activity-responsible-{{ $index }}" wire:model.defer="activities.{{ $index }}.responsible"
                                                       class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                                       placeholder="Nom ou rôle du responsable">
                                                @error('activities.' . $index . '.responsible') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                                <p class="mt-1 text-xs text-subtle">L'affectation précise pourra être affinée plus tard.</p>
                                            </div>
                                            <button type="button" wire:click="removeActivity({{ $index }})"
                                                    class="absolute top-2 right-2 text-error hover:text-error-dark text-lg">&times;</button>
                                        </div>
                                    @endforeach
                                @endif
                                <button type="button" wire:click="addActivity" class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent-dark transition-colors font-semibold">
                                    Ajouter une Activité
                                </button>
                                @error('activities') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Fenêtre 9 : Gestion des Incertitudes --}}
                        <div x-show="currentStep === 9" x-cloak class="space-y-6">
                            <h2 class="text-2xl font-semibold text-heading mb-4">Analyse et Stratégie des Risques</h2>
                            <p class="text-body mb-6">Identifiez les risques potentiels pour votre projet, leur impact et leur probabilité.</p>

                            <div class="bg-info/5 border border-info/20 text-info p-4 rounded-lg mb-6">
                                <p class="font-semibold">Conseil IA :</p>
                                <p>L'anticipation des risques est un gage de succès. L'IA peut vous aider à identifier des risques oubliés et à élaborer des plans d'atténuation efficaces.</p>
                            </div>

                            <h3 class="text-xl font-semibold text-heading mt-6 mb-3">Risques Identifiés</h3>
                            <div class="space-y-4">
                                @if(isset($risks) && is_array($risks))
                                    @foreach($risks as $index => $risk)
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 border border-border rounded-lg relative">
                                            <div>
                                                <label for="risk-description-{{ $index }}" class="block text-sm font-medium text-body">Description du Risque <span class="text-error">*</span></label>
                                                <textarea id="risk-description-{{ $index }}" wire:model.defer="risks.{{ $index }}.description" rows="2"
                                                          class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"
                                                          placeholder="Ex: Retard dans la livraison des équipements."></textarea>
                                                @error('risks.' . $index . '.description') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="risk-impact-{{ $index }}" class="block text-sm font-medium text-body">Impact <span class="text-error">*</span></label>
                                                <select id="risk-impact-{{ $index }}" wire:model.defer="risks.{{ $index }}.impact"
                                                        class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent">
                                                    <option value="">Sélectionner</option>
                                                    <option value="Faible">Faible</option>
                                                    <option value="Moyen">Moyen</option>
                                                    <option value="Élevé">Élevé</option>
                                                </select>
                                                @error('risks.' . $index . '.impact') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="risk-probability-{{ $index }}" class="block text-sm font-medium text-body">Probabilité <span class="text-error">*</span></label>
                                                <select id="risk-probability-{{ $index }}" wire:model.defer="risks.{{ $index }}.probability"
                                                        class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent">
                                                    <option value="">Sélectionner</option>
                                                    <option value="Faible">Faible</option>
                                                    <option value="Moyen">Moyen</option>
                                                    <option value="Élevé">Élevé</option>
                                                </select>
                                                @error('risks.' . $index . '.probability') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <button type="button" wire:click="removeRisk({{ $index }})"
                                                    class="absolute top-2 right-2 text-error hover:text-error-dark text-lg">&times;</button>
                                        </div>
                                    @endforeach
                                @endif
                                <button type="button" wire:click="addRisk" class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent-dark transition-colors font-semibold">
                                    Ajouter un Risque
                                </button>
                                @error('risks') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

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
                                {{-- Changed type to 'button' and added wire:click="nextStep" --}}
                                <button type="button" wire:click="nextStep" class="px-6 py-3 bg-accent text-white rounded-lg hover:bg-accent-dark transition-colors font-semibold">
                                    {{ __('common.next') }}
                                </button>
                            @else
                                {{-- This button will trigger the final submitForm --}}
                                <button type="submit" class="px-6 py-3 bg-success text-white rounded-lg hover:bg-success-dark transition-colors font-semibold">
                                    {{ __('common.submit') }}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>