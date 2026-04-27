<main class="lg:ml-64 pt-16 pb-12 min-h-screen bg-surface">
    <style>
        .wizard-container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        @media (min-width: 1024px) {
            .wizard-container {
                flex-direction: row;
                align-items: flex-start;
            }
            .sidebar-wizard {
                width: 320px !important;
                flex-shrink: 0;
                position: sticky;
                top: 6rem;
            }
            .content-wizard {
                flex-grow: 1;
                min-width: 0;
            }
        }
        .step-indicator {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.875rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .step-active {
            background-color: #0e7490; /* accent */
            border-color: #0e7490;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(14, 116, 144, 0.2);
        }
        .step-completed {
            background-color: white;
            border-color: #0e7490;
            color: #0e7490;
        }
        .step-future {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            color: #94a3b8;
        }
        .dark .step-future {
            background-color: #0f172a;
            border-color: #1e293b;
            color: #475569;
        }
    </style>

    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8" wire:loading.class="opacity-60">
        
        {{-- Global Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 p-5 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-3xl animate-bounce-subtle">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-rose-500 flex items-center justify-center text-white shadow-lg shadow-rose-500/30">
                        <x-dynamic-component component="lucide-alert-triangle" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-rose-800 dark:text-rose-300 uppercase tracking-tight">Oups ! Action Requise</h3>
                        <p class="text-xs text-rose-600 dark:text-rose-400 font-medium mt-0.5">
                            Il y a <span class="font-black">{{ $errors->count() }}</span> erreur(s) de validation. Veuillez vérifier tous les onglets.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="wizard-container" x-data="{
            currentStep: @entangle('currentStep'),
            totalSteps: @entangle('totalSteps'),
            stepDetails: @entangle('stepDetails'),
        }">
            
            {{-- WIZARD SIDEBAR --}}
            <aside class="sidebar-wizard bg-card rounded-[2rem] p-8 mt-8 shadow-sm border border-border-light">
                <div class="space-y-8">
                    <div>
                        <h2 class="text-[10px] font-black text-muted uppercase tracking-[0.3em] mb-6 pl-1">Le Processus</h2>
                        <div class="space-y-2">
                            <template x-for="(step, index) in stepDetails" :key="index">
                                <button type="button" 
                                    @click="(index + 1) <= currentStep && $wire.goToStep(index + 1)"
                                    class="w-full flex items-center gap-4 p-3 rounded-2xl transition-all group text-left"
                                    :class="{
                                        'bg-surface': (index + 1) === currentStep,
                                        'hover:bg-surface': (index + 1) <= currentStep
                                    }">
                                    
                                    <div class="step-indicator flex-shrink-0"
                                        :class="{
                                            'step-active': (index + 1) === currentStep && !step.has_error,
                                            'step-completed': (index + 1) < currentStep && !step.has_error,
                                            'step-future': (index + 1) > currentStep && !step.has_error,
                                            'bg-rose-100 text-rose-600 border border-rose-200 dark:bg-rose-900/30 dark:border-rose-800 dark:text-rose-400 shadow-sm': step.has_error
                                        }">
                                        <template x-if="(index + 1) < currentStep && !step.has_error"><x-dynamic-component component="lucide-check" class="w-3 h-3" /></template>
                                        <template x-if="step.has_error"><x-dynamic-component component="lucide-alert-circle" class="w-3 h-3" /></template>
                                        <template x-if="(index + 1) >= currentStep && !step.has_error"><span x-text="index + 1"></span></template>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="font-bold text-xs uppercase tracking-wide truncate" 
                                           :class="{
                                               'text-accent dark:text-accent-light': (index + 1) === currentStep && !step.has_error,
                                               'text-subtle': (index + 1) !== currentStep && !step.has_error,
                                               'text-rose-600 dark:text-rose-400': step.has_error
                                           }"
                                           x-text="step.title"></p>
                                        <p class="text-[9px] opacity-60 italic truncate font-medium" 
                                           :class="step.has_error ? 'text-rose-500' : ''"
                                           x-text="step.description"></p>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="pt-8 border-t border-border-light">
                        <div class="flex justify-between text-[10px] font-black text-muted mb-2.5 uppercase tracking-widest">
                            <span>Progression</span>
                            <span class="text-accent" x-text="Math.round((currentStep / totalSteps) * 100) + '%'"></span>
                        </div>
                        <div class="h-1.5 w-full bg-surface-alt rounded-full overflow-hidden">
                            <div class="h-full bg-accent transition-all duration-700 ease-out" :style="'width:' + (currentStep / totalSteps * 100) + '%'"></div>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- FORM CONTENT --}}
            <div class="content-wizard bg-card rounded-[2.5rem] p-8 mt-8 lg:p-12 shadow-sm border border-border-light">
                <header class="mb-12">
                    <h1 class="text-3xl font-black text-heading uppercase tracking-tighter">
                        {{ $projectId ? 'Mise à Jour' : 'Nouvelle Proposition' }}
                    </h1>
                    <div class="mt-2 w-12 h-1 bg-accent rounded-full"></div>
                </header>

                <form wire:submit.prevent="submitForm">
                    <div class="min-h-[500px]">
                        {{-- Step Content Wrappers - Forced visibility logic here for robustness --}}
                        <div x-show="currentStep === 1" x-cloak>@include('livewire.v-beta.proposal-project.step-1.index')</div>
                        <div x-show="currentStep === 2" x-cloak>@include('livewire.v-beta.proposal-project.step-2.index')</div>
                        <div x-show="currentStep === 3" x-cloak>@include('livewire.v-beta.proposal-project.step-3.index')</div>
                        <div x-show="currentStep === 4" x-cloak>@include('livewire.v-beta.proposal-project.step-4.index')</div>
                        <div x-show="currentStep === 5" x-cloak>@include('livewire.v-beta.proposal-project.step-5.index')</div>
                        <div x-show="currentStep === 6" x-cloak>@include('livewire.v-beta.proposal-project.step-final.index')</div>
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="mt-16 pt-10 border-t border-border-light flex items-center justify-between">
                        @if ($currentStep > 1)
                            <button type="button" wire:click="previousStep" class="text-xs font-black uppercase tracking-[0.2em] text-muted hover:text-indigo-600 transition-all flex items-center gap-2">
                                <x-dynamic-component component="lucide-arrow-left" class="w-4 h-4" /> Précédent
                            </button>
                        @else
                            <div></div>
                        @endif

                        <div class="flex items-center gap-4">
                            @if ($currentStep < $totalSteps)
                                <x-ui.button type="button" wire:click="nextStep" 
                                        size="lg"
                                        icon="chevron-right"
                                        loadingTarget="nextStep">
                                    Suivant
                                </x-ui.button>
                            @else
                                <x-ui.button type="submit"
                                        variant="accent"
                                        size="lg"
                                        icon="check-check"
                                        loadingTarget="submitForm"
                                        wire:loading.attr="disabled"
                                        :disabled="$isSubmitting">
                                    {{ $isSubmitting ? 'Enregistrement...' : 'Soumettre le projet' }}
                                </x-ui.button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
