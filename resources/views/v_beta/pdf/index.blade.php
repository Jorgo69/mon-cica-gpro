<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche de projet - {!! $project->title !!}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            #download-pdf { display: none; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="max-w-5xl mx-auto py-10 px-6 bg-white shadow-md rounded-lg" id="pdf-content">

        {{-- HEADER --}}
        <div class="text-center border-b border-gray-300 pb-6 mb-8">
            <h1 class="text-3xl font-bold text-blue-700">FICHE DE PROJET</h1>
            <p class="text-sm text-gray-500 mt-2">Document de référence - {!! now()->format('d/m/Y') !!}</p>
        </div>

        {{-- INFOS DE BASE --}}
        <div class="grid grid-cols-2 gap-6 mb-10">
            <div>
                <p class="font-semibold text-gray-700">Titre du projet :</p>
                <p class="text-gray-900">{!! $project->title !!}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-700">Code :</p>
                <p class="text-gray-900">{!! $project->project_code !!}</p>
            </div>
        </div>

        {{-- CONTEXTE --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-blue-600 border-b border-gray-300 pb-1 mb-3">Contexte</h2>
            <div class="prose max-w-none">{!! $project->description ?? '' !!}</div>
        </div>

        {{-- ANALYSE DU PROBLÈME --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-blue-600 border-b border-gray-300 pb-1 mb-3">Analyse du problème</h2>
            <div class="prose max-w-none">{!! $project->problem_analysis ?? '' !!}</div>
        </div>

        {{-- STRATEGIE --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-blue-600 border-b border-gray-300 pb-1 mb-3">Stratégie</h2>
            <div class="prose max-w-none">{!! $project->strategy ?? '' !!}</div>
        </div>

        {{-- JUSTIFICATION --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-blue-600 border-b border-gray-300 pb-1 mb-3">Justification</h2>
            <div class="prose max-w-none">{!! $project->justification ?? '' !!}</div>
        </div>

        {{-- OBJECTIFS + RÉSULTATS + ACTIVITÉS + BUDGET --}}
        @if($project->logicalFramework && $project->logicalFramework->specificObjectives->count())
            <div class="mb-8">
                <h2 class="text-xl font-bold text-blue-600 border-b border-gray-300 pb-1 mb-4">Objectifs spécifiques & Activités</h2>
                <table class="w-full border border-gray-300 text-sm">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="border border-gray-300 px-3 py-2 text-left">Objectif</th>
                            <th class="border border-gray-300 px-3 py-2 text-left">Résultats</th>
                            <th class="border border-gray-300 px-3 py-2 text-left">Activités & Budget</th>
                            <th class="border border-gray-300 px-3 py-2 text-left">Sous-activités</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->logicalFramework->specificObjectives as $objective)
                            <tr>
                                <td class="border border-gray-300 px-3 py-2 align-top">{!! $objective->description !!}</td>
                                <td class="border border-gray-300 px-3 py-2 align-top">
                                    @foreach($objective->results as $result)
                                        • {!! $result->description !!}<br>
                                    @endforeach
                                </td>
                                <td class="border border-gray-300 px-3 py-2 align-top">
                                    @foreach($objective->results as $result)
                                        @foreach($result->activities as $activity)
                                            <div class="mb-2">
                                                <span class="font-semibold">• {!! $activity->description !!}</span><br>
                                                <span class="text-gray-600">Budget : 
                                                    {{ number_format($activity->budget, 0, ',', ' ') }} FCFA
                                                </span>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </td>
                                <td class="border border-gray-300 px-3 py-2 align-top">
                                    @foreach($objective->results as $result)
                                        @foreach($result->activities as $activity)
                                            @foreach($activity->subActivities as $sub)
                                                • {!! $sub->description !!}<br>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- CHAMPS DYNAMIQUES --}}
        @if($dynamicFormFields)
            @foreach($dynamicFormFields as $section => $fields)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-blue-600 border-b border-gray-300 pb-1 mb-3">
                        {{ ucfirst($section) }}
                    </h2>
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
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="font-medium text-gray-800">{!! $fieldDef['question_text'] !!}</p>
                                    <p class="text-gray-700 mt-1">{!! $value !!}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

        {{-- DOCUMENTS --}}
        @if($project->documents->count())
            <div class="mb-8">
                <h2 class="text-xl font-bold text-blue-600 border-b border-gray-300 pb-1 mb-3">Documents associés</h2>
                <ul class="list-disc list-inside text-gray-700">
                    @foreach($project->documents as $doc)
                        <li>{!! $doc->name !!} ({!! $doc->type !!})</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

    {{-- Bouton download --}}
    <div class="text-center mt-6">
        <button id="download-pdf" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Télécharger en PDF
        </button>
    </div>

    {{-- Script html2pdf --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.getElementById("download-pdf").addEventListener("click", () => {
            const element = document.getElementById("pdf-content");
            html2pdf().from(element).set({
                margin: 10,
                filename: 'projet-{{ $project->id }}.pdf',
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            }).save();
        });
    </script>
</body>
</html>
