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
                <x-ui.button tag="a" :href="route('projects.export.pdf', $project->id)" variant="accent" icon="file-down" size="sm">
                    Exporter PDF
                </x-ui.button>
                <x-ui.button tag="a" :href="route('project.list')" variant="ghost" icon="arrow-left" size="sm" wire:navigate>
                    Retour
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        <div class="space-y-6">

            {{-- Section 1: Informations Générales --}}
            <x-ui.section title="Informations Générales" icon="info">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Titre</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $project->title }}</span>
                        </div>
                        @if($project->short_title)
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Titre court</span>
                            <span class="text-slate-600 dark:text-slate-300">{{ $project->short_title }}</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Statut</span>
                            @php
                                $statusEnum = $project->status instanceof \App\Enums\ProjectStatus ? $project->status : \App\Enums\ProjectStatus::tryFrom($project->status);
                                $badgeVariant = $statusEnum ? $statusEnum->color() : 'slate';
                            @endphp
                            <x-ui.badge :variant="$badgeVariant">{{ $statusEnum ? $statusEnum->label() : $project->status }}</x-ui.badge>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Période</span>
                            <span class="text-slate-600 dark:text-slate-300">
                                {{ $project->start_date?->format('d/m/Y') }} → {{ $project->end_date?->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Créé le</span>
                            <span class="text-slate-600 dark:text-slate-300">{{ $project->created_at?->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Mis à jour</span>
                            <span class="text-slate-600 dark:text-slate-300">{{ $project->updated_at?->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($project->creator)
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Créé par</span>
                            <span class="text-slate-600 dark:text-slate-300">{{ $project->creator->name }}</span>
                        </div>
                        @endif
                        @if($project->projectType)
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-28">Type</span>
                            <x-ui.badge variant="accent" size="sm">{{ $project->projectType->name }}</x-ui.badge>
                        </div>
                        @endif
                    </div>
                </div>

                @if($project->description)
                    <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Description</p>
                        <div class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl text-sm leading-relaxed">
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
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Description du contexte</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl text-justify leading-relaxed">{{ $project->projectContext->context_description }}</p>
                        </div>
                        @endif
                        @if($project->justification)
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Justification</p>
                            <div class="text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl leading-relaxed">{!! $project->justification !!}</div>
                        </div>
                        @endif
                        @if($project->strategy)
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Stratégie</p>
                            <div class="text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl leading-relaxed">{!! $project->strategy !!}</div>
                        </div>
                        @endif
                        @if($project->problem_analysis)
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Analyse du problème</p>
                            <div class="text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl leading-relaxed">{!! $project->problem_analysis !!}</div>
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
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ $fieldDef['question_text'] }}</p>
                                        <p class="text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl leading-relaxed">{{ $value }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </x-ui.section>
                @endforeach
            @endif

            {{-- Section 4: Cadre Logique --}}
            @if($project->logicalFramework)

                {{-- Objectif Général --}}
                <x-ui.section title="Objectif Général" icon="target">
                    <div class="overflow-x-auto -mx-6">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800">
                                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-48">Champs</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Valeur</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50 text-slate-600 dark:text-slate-300">
                                <tr><td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-200">Description</td><td class="px-6 py-3 text-justify">{!! $project->logicalFramework->general_objective !!}</td></tr>
                                <tr><td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-200">Indicateurs</td><td class="px-6 py-3 text-justify">{!! $project->logicalFramework->general_obj_indicators !!}</td></tr>
                                <tr><td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-200">Sources de vérification</td><td class="px-6 py-3 text-justify">{!! $project->logicalFramework->general_obj_verification_sources !!}</td></tr>
                                <tr><td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-200">Hypothèses</td><td class="px-6 py-3 text-justify">{!! $project->logicalFramework->assumptions !!}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </x-ui.section>

                {{-- Objectifs Spécifiques --}}
                @if($project->logicalFramework->specificObjectives->isNotEmpty())
                    <x-ui.section title="Objectifs Spécifiques" icon="list-ordered">
                        <div class="overflow-x-auto -mx-6">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-100 dark:border-slate-800">
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Champs</th>
                                        @foreach($project->logicalFramework->specificObjectives as $obj)
                                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Objectif {{ $loop->iteration }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50 text-slate-600 dark:text-slate-300">
                                    <tr><td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-200">Description</td>@foreach($project->logicalFramework->specificObjectives as $obj)<td class="px-6 py-3">{!! $obj->description !!}</td>@endforeach</tr>
                                    <tr><td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-200">Indicateurs</td>@foreach($project->logicalFramework->specificObjectives as $obj)<td class="px-6 py-3">{!! $obj->indicators !!}</td>@endforeach</tr>
                                    <tr><td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-200">Sources</td>@foreach($project->logicalFramework->specificObjectives as $obj)<td class="px-6 py-3">{!! $obj->verification_sources !!}</td>@endforeach</tr>
                                    <tr><td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-200">Hypothèses</td>@foreach($project->logicalFramework->specificObjectives as $obj)<td class="px-6 py-3">{!! $obj->assumptions !!}</td>@endforeach</tr>
                                </tbody>
                            </table>
                        </div>
                    </x-ui.section>
                @endif

                {{-- Résultats Attendus --}}
                @php
                    $results = collect();
                    foreach($project->logicalFramework->specificObjectives as $obj){
                        $results = $results->merge($obj->results);
                    }
                @endphp
                @if($results->isNotEmpty())
                    <x-ui.section title="Résultats Attendus" icon="check-square">
                        <div class="overflow-x-auto -mx-6">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-100 dark:border-slate-800">
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Champs</th>
                                        @foreach($results as $result)
                                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Résultat {{ $loop->iteration }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50 text-slate-600 dark:text-slate-300">
                                    <tr><td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-200">Description</td>@foreach($results as $result)<td class="px-6 py-3">{!! $result->description !!}</td>@endforeach</tr>
                                </tbody>
                            </table>
                        </div>
                    </x-ui.section>
                @endif

                {{-- Activités --}}
                @php $activities = $project->getAllActivities(); @endphp
                @if($activities->isNotEmpty())
                    <x-ui.section title="Liste des Activités" icon="list-checks">
                        <div class="overflow-x-auto -mx-6">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-100 dark:border-slate-800">
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Description</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Responsable</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Début</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Fin</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50 text-slate-600 dark:text-slate-300">
                                    @foreach($activities as $activity)
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                            <td class="px-6 py-3">{!! $activity->description !!}</td>
                                            <td class="px-6 py-3">{{ $activity->responsibleUser->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-3">{{ $activity->start_date ?? 'N/A' }}</td>
                                            <td class="px-6 py-3">{{ $activity->end_date ?? 'N/A' }}</td>
                                            <td class="px-6 py-3">
                                                <x-ui.badge variant="slate" size="sm">{{ $activity->status ?? 'N/A' }}</x-ui.badge>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-ui.section>
                @endif
            @endif

            {{-- Section: Documents --}}
            @if($project->documents->isNotEmpty())
                <x-ui.section title="Documents Associés" icon="paperclip">
                    <ul class="space-y-2">
                        @foreach($project->documents as $document)
                            <li>
                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" 
                                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                                    <x-lucide-file class="w-4 h-4 text-accent opacity-60" />
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-accent transition-colors">
                                        {{ $document->file_name }}
                                    </span>
                                    <x-ui.badge variant="slate" size="sm">{{ strtoupper($document->file_mime_type) }}</x-ui.badge>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </x-ui.section>
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
