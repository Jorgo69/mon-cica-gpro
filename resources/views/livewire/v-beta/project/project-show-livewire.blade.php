<main class="lg:ml-64 pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="p-6">
        @if ($project)
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">Détails du Projet : <span class="text-blue-600">{{ $project->title }}</span></h1>
                    <p class="text-gray-600 dark:text-gray-300 mb-8">{{ $project->short_title ? '('.$project->short_title.')' : '' }} Code: {{ $project->project_code }}</p>

                    <a href="{{ route('projects.export.pdf', $project->id) }}" class="btn btn-primary">
                        Exporter PDF
                    </a>

                    <div class="space-y-8">

                        {{-- Section 1: Informations Générales --}}
                      
                        <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-2xl shadow-lg space-y-6">
                            <!-- Titre -->
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center border-b pb-3">
                                <i class="fas fa-info-circle mr-3 text-blue-600"></i> Informations Générales
                            </h2>

                            <!-- Statut et Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <p>
                                        <span class="font-medium">Statut :</span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            {{
                                                $project->status === 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' :
                                                ($project->status === 'draft' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' :
                                                ($project->status === 'Terminé' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                                ($project->status === 'on_hold' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200')))
                                            }}">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </p>
                                    <p><span class="font-medium">Date de Début :</span> {{ $project->start_date?->format('d/m/Y') }}</p>
                                    <p><span class="font-medium">Date de Fin :</span> {{ $project->end_date?->format('d/m/Y') }}</p>
                                </div>

                                <div class="space-y-3">
                                    <p><span class="font-medium">Créé le :</span> {{ $project->created_at?->format('d/m/Y H:i') }}</p>
                                    <p><span class="font-medium">Dernière mise à jour :</span> {{ $project->updated_at?->format('d/m/Y H:i') }}</p>
                                    @if($project->creator)
                                        <p><span class="font-medium">Créé par :</span> {{ $project->creator->name }}</p>
                                    @endif
                                    @if($project->updater)
                                        <p><span class="font-medium">Mis à jour par :</span> {{ $project->updater->name }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Type de Projet -->
                            @if($project->projectType)
                                <div class="border-t pt-4">
                                    <p><span class="font-medium dark:text-gray-100">Type de Projet :</span> {{ $project->projectType->name }}</p>
                                </div>
                            @endif

                            <!-- Description -->
                            @if($project->description)
                                <div class="border-t pt-4">
                                    <p class="font-medium dark:text-gray-100">Description Générale :</p>
                                    <p class="mt-2 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg text-sm leading-relaxed text-gray-700 dark:text-gray-300 shadow-inner">
                                        {{ $project->description }}
                                    </p>
                                </div>
                            @endif
                        </div>


                        {{-- Section 2: Contexte du projet --}}
                        @if($project->projectContext)
                            <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <i class="fas fa-file-alt mr-3 text-blue-600"></i> Contexte du Projet
                                </h2>
                                <p class="text-gray-700 dark:text-gray-300">
                                    <strong class="font-medium">Description :</strong>
                                    <p class="mt-1 p-3 bg-gray-50 dark:bg-gray-900 rounded-md text-justify  text-lg dark:text-gray-300">{{ $project->projectContext->context_description }}</p>
                                </p>
                                <p class="text-gray-700 dark:text-gray-300">
                                    <strong class="font-medium">Justification :</strong>
                                    <p class="mt-1 p-3 bg-gray-50 dark:bg-gray-900 rounded-md text-sm dark:text-gray-300">{!! $project->justification !!}</p>
                                </p>
                                <p class="text-gray-700 dark:text-gray-300">
                                    <strong class="font-medium">Strategie :</strong>
                                    <p class="mt-1 p-3 bg-gray-50 dark:bg-gray-900 rounded-md text-sm dark:text-gray-300">{!! $project->strategy !!}</p>
                                </p>
                                <p class="text-gray-700 dark:text-gray-300">
                                    <strong class="font-medium">Analyse du probleme :</strong>
                                    <p class="mt-1 p-3 bg-gray-50 dark:bg-gray-900 rounded-md text-sm dark:text-gray-300">{!! $project->problem_analysis !!}</p>
                                </p>
                            </div>
                        @endif

                        {{-- Section 3: Champs Dynamiques (traitement des délimiteurs) --}}
                        @if($dynamicFormFields)
                            @foreach($dynamicFormFields as $section => $fields)
                                <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow">
                                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                        <i class="fas fa-cogs mr-3 text-blue-600"></i> {{ ucfirst($section) }}
                                    </h2>
                                    <div class="space-y-4 text-gray-700 dark:text-gray-300">
                                        @foreach($fields as $fieldDef)
                                            @php
                                                $targetField = $fieldDef['target_project_field'];
                                                $value = null;
                                                // Logique pour extraire la valeur délimitée
                                                if (isset($project->$targetField)) {
                                                    $pattern = '/' . preg_quote($fieldDef['delimiter_start'], '/') . '(.*?)' . preg_quote($fieldDef['delimiter_end'], '/') . '/s';
                                                    if (preg_match($pattern, $project->$targetField, $matches)) {
                                                        $value = $matches[1];
                                                    }
                                                }
                                            @endphp
                                            @if($value)
                                                <div>
                                                    <strong class="font-medium">{{ $fieldDef['question_text'] }} :</strong>
                                                    <p class="mt-1 p-3 bg-gray-50 dark:bg-gray-900 rounded-md text-sm dark:text-gray-400">{{ $value }}</p>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{-- Section 4: Cadre Logique
                        @if($project->logicalFramework)
                            <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <i class="fas fa-bullseye mr-3 text-blue-600"></i> Cadre Logique
                                </h2>

                                <div class="mb-6">
                                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">But Général</h3>
                                    <p class="mt-1 p-3 bg-gray-50 dark:bg-gray-900 rounded-md text-sm text-gray-700 dark:text-gray-300">{{ $project->logicalFramework->general_objective }}</p>
                                </div>

                                @if($project->logicalFramework->specificObjectives->isNotEmpty())
                                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Objectifs Spécifiques</h3>
                                    <div class="space-y-4">
                                        @foreach($project->logicalFramework->specificObjectives as $objective)
                                            <div class="bg-white dark:bg-gray-900 p-4 rounded-md shadow-sm border border-gray-200 dark:border-gray-700">
                                                <p><strong class="font-medium">Description :</strong> {{ $objective->description }}</p>

                                                @if($objective->results->isNotEmpty())
                                                    <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mt-4 mb-2">Résultats Attendus</h4>
                                                    <ul class="list-disc list-inside space-y-2 text-gray-600 dark:text-gray-400">
                                                        @foreach($objective->results as $result)
                                                            <li>
                                                                <strong class="font-medium">{!! $result->description !!}</strong>
                                                                <p class="text-sm italic">Indicateurs : {{ $result->indicators }}</p>
                                                                @if($result->activities->isNotEmpty())
                                                                    <h5 class="text-base font-semibold text-gray-600 dark:text-gray-400 mt-2 mb-1">Activités</h5>
                                                                    <ul class="list-disc list-inside ml-4 space-y-1 text-gray-500 dark:text-gray-500">
                                                                        @foreach($result->activities as $activity)
                                                                            <li>{{ $activity->description }} (Responsable: {{ $activity->responsibleUser->name ?? 'N/A' }})</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif --}}

                        @if($project->logicalFramework)

                            {{-- 🎯 Objectif Général --}}
                            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                🎯 Objectif Général
                            </h2>
                             <table class="min-w-full border border-gray-300 dark:border-gray-700 text-sm">
                                <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                        <th class="px-4 py-2 text-left">Champs</th>
                                        <th class="px-4 py-2 text-left">Valeur</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                                    <tr class="border-t border-gray-300 dark:border-gray-700">
                                        <td class="font-medium px-4 py-2 align-top">Description</td>
                                        <td class="px-4 py-2 text-justify">{!! $project->logicalFramework->general_objective !!}</td>
                                    </tr>
                                    <tr class="border-t border-gray-300 dark:border-gray-700">
                                        <td class="font-medium px-4 py-2 align-top">Indicateurs</td>
                                        <td class="px-4 py-2 text-justify">{!! $project->logicalFramework->general_obj_indicators !!}</td>
                                    </tr>
                                    <tr class="border-t border-gray-300 dark:border-gray-700">
                                        <td class="font-medium px-4 py-2 align-top">Sources de vérification</td>
                                        <td class="px-4 py-2 text-justify">{!! $project->logicalFramework->general_obj_verification_sources !!}</td>
                                    </tr>
                                    <tr class="border-t border-gray-300 dark:border-gray-700">
                                        <td class="font-medium px-4 py-2 align-top">Hypothèses</td>
                                        <td class="px-4 py-2 text-justify">{!! $project->logicalFramework->assumptions !!}</td>
                                    </tr>
                                </tbody>
                            </table>

                            {{-- 📌 Objectifs Spécifiques --}}
                            @if($project->logicalFramework->specificObjectives->isNotEmpty())
                                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-4">
                                    📌 Objectifs Spécifiques
                                </h2>
                                <div class="overflow-x-auto">
                                    <table class="w-full border border-gray-300 dark:border-gray-700 text-sm text-left">
                                        <thead class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            <tr>
                                                <th class="px-4 py-2">Champs</th>
                                                @foreach($project->logicalFramework->specificObjectives as $obj)
                                                    <th class="px-4 py-2">Objectif {!! $loop->iteration !!}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                            <tr>
                                                <td class="font-medium px-4 py-2">Description</td>
                                                @foreach($project->logicalFramework->specificObjectives as $obj)
                                                    <td class="px-4 py-2">{!! $obj->description !!}</td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td class="font-medium px-4 py-2">Indicateurs</td>
                                                @foreach($project->logicalFramework->specificObjectives as $obj)
                                                    <td class="px-4 py-2">{!! $obj->indicators !!}</td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td class="font-medium px-4 py-2">Sources de Vérification</td>
                                                @foreach($project->logicalFramework->specificObjectives as $obj)
                                                    <td class="px-4 py-2">{!! $obj->verification_sources !!}</td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td class="font-medium px-4 py-2">Hypothèses</td>
                                                @foreach($project->logicalFramework->specificObjectives as $obj)
                                                    <td class="px-4 py-2">{!! $obj->assumptions !!}</td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            {{-- ✅ Résultats Attendus --}}
                            @php
                                $results = collect();
                                foreach($project->logicalFramework->specificObjectives as $obj){
                                    $results = $results->merge($obj->results);
                                }
                            @endphp

                            @if($results->isNotEmpty())
                                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-4">
                                    ✅ Résultat Attendu
                                </h2>
                                <div class="overflow-x-auto">
                                    <table class="w-full border border-gray-300 dark:border-gray-700 text-sm text-left">
                                        <thead class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            <tr>
                                                <th class="px-4 py-2">Champs</th>
                                                @foreach($results as $result)
                                                    <th class="px-4 py-2">Résultat </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="font-medium px-4 py-2">Description</td>
                                                @foreach($results as $result)
                                                    <td class="px-4 py-2">{!! $result->description !!}</td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            {{-- 🛠️ Activités --}}
                            @php $activities = $project->getAllActivities(); @endphp

                            @if($activities->isNotEmpty())
                                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-4">
                                    🛠️ Liste des Activités
                                </h2>
                                <div class="overflow-x-auto">
                                    <table class="w-full border border-gray-300 dark:border-gray-700 text-sm text-left">
                                        <thead class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            <tr>
                                                <th class="px-4 py-2">Description</th>
                                                <th class="px-4 py-2">Responsable</th>
                                                <th class="px-4 py-2">Date Début</th>
                                                <th class="px-4 py-2">Date Fin</th>
                                                <th class="px-4 py-2">Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($activities as $activity)
                                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                                    <td class="px-4 py-2">{!! $activity->description !!}</td>
                                                    <td class="px-4 py-2">{!! $activity->responsibleUser->name ?? 'N/A' !!}</td>
                                                    <td class="px-4 py-2">{!! $activity->start_date ?? 'N/A' !!}</td>
                                                    <td class="px-4 py-2">{!! $activity->end_date ?? 'N/A' !!}</td>
                                                    <td class="px-4 py-2">{!!$activity->status ?? 'N/A' !!}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        @endif



                        {{-- Section 6: Documents Associés --}}
                        @if($project->documents->isNotEmpty())
                            <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <i class="fas fa-file-alt mr-3 text-blue-600"></i> Documents Associés
                                </h2>
                                <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300">
                                    @foreach($project->documents as $document)
                                        <li>
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                                {{ $document->file_name }} ({{ strtoupper($document->file_mime_type) }})
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">
                            @can('update', $project)
                            <a href="{{ route('creator.proposal.project.edit', ['projectId' => $project->id ]) }}" class="px-4 py-3 sm:px-6 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-semibold text-center">
                                Modifier
                            </a>
                            @endcan

                            <a href="{{ route('project.list') }}" class="px-4 py-3 sm:px-6 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-semibold text-center">
                                Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center p-8 bg-white dark:bg-gray-900 rounded-lg shadow">
                <p class="text-xl text-gray-700 dark:text-gray-300">Projet non trouvé.</p>
            </div>
        @endif
    </div>
</main>
