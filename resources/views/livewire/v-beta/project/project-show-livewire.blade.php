<x-ui.page-layout>
    @if ($project)
        {{-- Page Header --}}
        <x-ui.page-header title="Détails du Projet" :subtitle="$project->project_code">
            <x-slot:actions>
                @can('update', $project)
                    <x-ui.button tag="a" :href="route('creator.proposal.project.edit', ['projectId' => $project->id])" variant="outline" icon="pencil" size="sm" wire:navigate>
                        Modifier
                    </x-ui.button>
                @endcan
                <x-ui.button tag="a" :href="route('projects.export.pdf', [ $project->id, 'template' => 'modern'])" variant="accent" icon="file-down" size="sm">
                    Rapport Premium
                </x-ui.button>
                <x-ui.button tag="a" :href="route('project.list')" variant="ghost" icon="arrow-left" size="sm" wire:navigate>
                    Retour
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        {{-- Tabs Navigation --}}
        <div class="flex items-center gap-2 border-b border-border-light mb-6 overflow-x-auto no-scrollbar">
            <button wire:click="switchTab('overview')" 
                class="px-4 py-2 text-sm font-medium transition-colors relative whitespace-nowrap {{ $activeTab === 'overview' ? 'text-accent' : 'text-subtle hover:text-body' }}">
                Aperçu
                @if($activeTab === 'overview') <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-accent"></div> @endif
            </button>
            <button wire:click="switchTab('logframe')" 
                class="px-4 py-2 text-sm font-medium transition-colors relative whitespace-nowrap {{ $activeTab === 'logframe' ? 'text-accent' : 'text-subtle hover:text-body' }}">
                Cadre Logique
                @if($activeTab === 'logframe') <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-accent"></div> @endif
            </button>
            <button wire:click="switchTab('documents')" 
                class="px-4 py-2 text-sm font-medium transition-colors relative whitespace-nowrap {{ $activeTab === 'documents' ? 'text-accent' : 'text-subtle hover:text-body' }}">
                Documents
                @if($activeTab === 'documents') <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-accent"></div> @endif
            </button>
            <button wire:click="switchTab('analytics')" 
                class="px-4 py-2 text-sm font-medium transition-colors relative whitespace-nowrap {{ $activeTab === 'analytics' ? 'text-accent' : 'text-subtle hover:text-body' }}">
                Analyses
                @if($activeTab === 'analytics') <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-accent"></div> @endif
            </button>
            <button wire:click="switchTab('tracking')"
                class="px-4 py-2 text-sm font-medium transition-colors relative whitespace-nowrap {{ $activeTab === 'tracking' ? 'text-accent' : 'text-subtle hover:text-body' }}">
                Suivi
                @if($activeTab === 'tracking') <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-accent"></div> @endif
            </button>
            <button wire:click="switchTab('history')"
                class="px-4 py-2 text-sm font-medium transition-colors relative whitespace-nowrap {{ $activeTab === 'history' ? 'text-accent' : 'text-subtle hover:text-body' }}">
                Historique
                @if($activeTab === 'history') <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-accent"></div> @endif
            </button>
        </div>

        <div class="space-y-6">

            @if($activeTab === 'overview')
                {{-- Section 1: Informations Générales --}}
                <x-ui.section title="Informations Générales" icon="info">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">Titre</span>
                                <span class="font-semibold text-heading">{{ $project->title }}</span>
                            </div>
                            @if($project->short_title)
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">Titre court</span>
                                <span class="text-body">{{ $project->short_title }}</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">Statut</span>
                                @php
                                    $statusEnum = $project->status instanceof \App\Enums\ProjectStatus ? $project->status : \App\Enums\ProjectStatus::tryFrom($project->status);
                                    $badgeVariant = $statusEnum ? $statusEnum->color() : 'slate';
                                @endphp
                                <x-ui.badge :variant="$badgeVariant">{{ $statusEnum ? $statusEnum->label() : $project->status }}</x-ui.badge>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">Période</span>
                                <span class="text-body">
                                    {{ $project->start_date?->format('d/m/Y') }} → {{ $project->end_date?->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">Créé le</span>
                                <span class="text-body">{{ $project->created_at?->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">Mis à jour</span>
                                <span class="text-body">{{ $project->updated_at?->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($project->creator)
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">Créé par</span>
                                <span class="text-body">{{ $project->creator->name }}</span>
                            </div>
                            @endif
                            @if($project->projectType)
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">Type</span>
                                <x-ui.badge variant="accent" size="sm">{{ $project->projectType->name }}</x-ui.badge>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($project->description)
                        <div class="mt-6 pt-5 border-t border-border-light">
                            <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-2">Description</p>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-body bg-surface dark:bg-surface-alt/50 p-4 rounded-xl text-sm leading-relaxed">
                                {!! $project->description !!}
                            </div>
                        </div>
                    @endif
                </x-ui.section>

                {{-- Section 2: Contexte du projet --}}
                @if($project->projectContext)
                    <x-ui.section title="Contexte du Projet" icon="file-text">
                        <div class="space-y-4">
                            @if($project->projectContext->context_description)
                            <div>
                                <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">Description du contexte</p>
                                <p class="text-sm text-body bg-surface dark:bg-surface-alt/50 p-4 rounded-xl text-justify leading-relaxed">{{ $project->projectContext->context_description }}</p>
                            </div>
                            @endif
                            @if($project->justification)
                            <div>
                                <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">Justification</p>
                                <div class="text-sm text-body bg-surface dark:bg-surface-alt/50 p-4 rounded-xl leading-relaxed">{!! $project->justification !!}</div>
                            </div>
                            @endif
                            @if($project->strategy)
                            <div>
                                <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">Stratégie</p>
                                <div class="text-sm text-body bg-surface dark:bg-surface-alt/50 p-4 rounded-xl leading-relaxed">{!! $project->strategy !!}</div>
                            </div>
                            @endif
                            @if($project->problem_analysis)
                            <div>
                                <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">Analyse du problème</p>
                                <div class="text-sm text-body bg-surface dark:bg-surface-alt/50 p-4 rounded-xl leading-relaxed">{!! $project->problem_analysis !!}</div>
                            </div>
                            @endif
                        </div>
                    </x-ui.section>
                @endif

                {{-- Section 3: Champs Dynamiques --}}
                @if($dynamicFormFields)
                    @foreach($dynamicFormFields as $section => $fields)
                        <x-ui.section :title="ucfirst($section)" icon="puzzle">
                            <div class="space-y-4">
                                @foreach($fields as $fieldDef)
                                    @php
                                        $targetField = $fieldDef['target_project_field'];
                                        $value = null;
                                        if (isset($project->$targetField)) {
                                            $pattern = '/' . preg_quote($fieldDef['delimiter_start'], '/') . '(.*?)' . preg_quote($fieldDef['delimiter_end'], '/') . '/s';
                                            if (preg_match($pattern, $project->$targetField, $matches)) {
                                                $value = $matches[1];
                                            }
                                        }
                                    @endphp
                                    @if($value)
                                        <div>
                                            <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">{{ $fieldDef['question_text'] }}</p>
                                            <p class="text-sm text-body bg-surface dark:bg-surface-alt/50 p-4 rounded-xl leading-relaxed">{{ $value }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </x-ui.section>
                    @endforeach
                @endif

                @if($activeTab === 'logframe')
                    {{-- Section 4: Cadre Logique --}}
                    @if($project->logicalFramework)

                        {{-- Selecteur de format --}}
                        <div class="flex items-center gap-2 mb-6">
                            <span class="text-[10px] font-black text-muted uppercase tracking-widest">Format :</span>
                            @foreach(\App\Enums\LogframeDisplayFormat::cases() as $format)
                                <button type="button"
                                    wire:click="setLogframeFormat('{{ $format->value }}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $logframeFormat === $format->value ? 'bg-accent text-white shadow-sm' : 'bg-surface-alt text-subtle hover:bg-border dark:hover:bg-surface-alt' }}">
                                    <x-dynamic-component :component="'lucide-' . $format->icon()" class="w-3.5 h-3.5" />
                                    {{ $format->label() }}
                                </button>
                            @endforeach
                        </div>

                        {{-- Contenu selon le format choisi --}}
                        @include('livewire.v-beta.project.logframe.format-' . $logframeFormat)

                    @endif

                @endif

                @if($activeTab === 'documents')
                    {{-- Section: Documents --}}
                    @if($project->documents->isNotEmpty())
                        <x-ui.section title="Documents Associés" icon="paperclip">
                            <ul class="space-y-2">
                                @foreach($project->documents as $document)
                                    <li>
                                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" 
                                        class="flex items-center gap-3 p-3 rounded-xl hover:bg-surface dark:hover:bg-surface-alt/50 transition-colors group">
                                            <x-lucide-file class="w-4 h-4 text-accent opacity-60" />
                                            <span class="text-sm font-medium text-body group-hover:text-accent transition-colors">
                                                {{ $document->file_name }}
                                            </span>
                                            <x-ui.badge variant="slate" size="sm">{{ strtoupper($document->file_mime_type) }}</x-ui.badge>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </x-ui.section>
                    @else
                        <x-ui.empty-state icon="file-question" title="Aucun document" description="Il n'y a pas encore de documents associés à ce projet." />
                    @endif
                @endif

                @if($activeTab === 'analytics')
                    {{-- Section: Analyses Spécifiques --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <x-ui.section title="Répartition des Ressources" icon="pie-chart">
                            @php
                                $resourceData = $project->resources->groupBy('type')->map(fn($group) => $group->sum('total_cost'));
                            @endphp
                            <x-ui.chart 
                                type="pie" 
                                height="300px"
                                :labels="$resourceData->keys()->toArray()"
                                :datasets="[
                                    [
                                        'data' => $resourceData->values()->toArray(),
                                        'backgroundColor' => ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'],
                                        'borderWidth' => 0
                                    ]
                                ]"
                            />
                        </x-ui.section>

                        <x-ui.section title="Santé Budgétaire" icon="banknote">
                            <div class="flex flex-col items-center justify-center h-[300px] space-y-4">
                                <div class="text-center">
                                    <p class="text-xs text-subtle uppercase font-black tracking-widest">Budget Total</p>
                                    <p class="text-3xl font-black text-primary">{{ number_format($project->resources->sum('total_cost'), 0, ',', ' ') }} FCFA</p>
                                </div>
                                <div class="w-full bg-surface-alt h-4 rounded-full overflow-hidden">
                                    <div class="bg-accent h-full" style="width: 100%"></div>
                                </div>
                                <p class="text-xs text-subtle italic">Consommation du budget : 100% planifié</p>
                            </div>
                        </x-ui.section>
                    </div>
                @endif

                @if($activeTab === 'tracking')
                    {{-- Section: Suivi de progression --}}
                    @livewire('v-beta.project.project-progress-comparison-livewire', ['projectId' => $project->id])
                @endif

                @if($activeTab === 'history')
                    {{-- Section: Historique --}}
                    <x-ui.section title="Audit & Traçabilité" icon="history">
                        @livewire('v-beta.audit.activity-history-livewire', ['subject' => $project])
                    </x-ui.section>
                @endif

            @endif

        </div>
    @else
        <x-ui.empty-state icon="folder-x" title="Projet non trouvé" description="Le projet demandé n'existe pas ou a été supprimé.">
            <x-ui.button tag="a" :href="route('project.list')" variant="outline" icon="arrow-left" size="sm" wire:navigate>
                Retour à la liste
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-ui.page-layout>
