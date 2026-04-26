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
<body class="bg-surface text-heading antialiased">
    <div class="max-w-5xl mx-auto py-10 px-6 bg-white shadow-md rounded-lg" id="pdf-content">

        {{-- HEADER --}}
        <div class="text-center border-b border-border pb-6 mb-8">
            <h1 class="text-3xl font-bold text-accent">FICHE DE PROJET</h1>
            <p class="text-sm text-subtle mt-2">Document de référence - {!! now()->format('d/m/Y') !!}</p>
        </div>

        {{-- INFOS DE BASE --}}
        <div class="grid grid-cols-2 gap-6 mb-10">
            <div>
                <p class="font-semibold text-body">Titre du projet :</p>
                <p class="text-heading">{!! $project->title !!}</p>
            </div>
            <div>
                <p class="font-semibold text-body">Code :</p>
                <p class="text-heading">{!! $project->project_code !!}</p>
            </div>
        </div>

        {{-- CONTEXTE --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-accent border-b border-border pb-1 mb-3">Contexte</h2>
            <div class="prose max-w-none">{!! $project->description ?? '' !!}</div>
        </div>

        {{-- ANALYSE DU PROBLÈME --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-accent border-b border-border pb-1 mb-3">Analyse du problème</h2>
            <div class="prose max-w-none">{!! $project->problem_analysis ?? '' !!}</div>
        </div>

        {{-- STRATEGIE --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-accent border-b border-border pb-1 mb-3">Stratégie</h2>
            <div class="prose max-w-none">{!! $project->strategy ?? '' !!}</div>
        </div>

        {{-- JUSTIFICATION --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-accent border-b border-border pb-1 mb-3">Justification</h2>
            <div class="prose max-w-none">{!! $project->justification ?? '' !!}</div>
        </div>

        {{-- OBJECTIFS + RÉSULTATS + ACTIVITÉS + BUDGET --}}
        @if($project->logicalFramework && $project->logicalFramework->specificObjectives->count())
            <div class="mb-8">
                <h2 class="text-xl font-bold text-accent border-b border-border pb-1 mb-4">Objectifs spécifiques & Activités</h2>
                <table class="w-full border border-border text-sm">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="border border-border px-3 py-2 text-left">Objectif</th>
                            <th class="border border-border px-3 py-2 text-left">Résultats</th>
                            <th class="border border-border px-3 py-2 text-left">Activités & Budget</th>
                            <th class="border border-border px-3 py-2 text-left">Sous-activités</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->logicalFramework->specificObjectives as $objective)
                            <tr>
                                <td class="border border-border px-3 py-2 align-top">{!! $objective->description !!}</td>
                                <td class="border border-border px-3 py-2 align-top">
                                    @foreach($objective->results as $result)
                                        • {!! $result->description !!}<br>
                                    @endforeach
                                </td>
                                <td class="border border-border px-3 py-2 align-top">
                                    @foreach($objective->results as $result)
                                        @foreach($result->activities as $activity)
                                            <div class="mb-2">
                                                <span class="font-semibold">• {!! $activity->description !!}</span><br>
                                                <span class="text-subtle">Budget : 
                                                    {{ number_format($activity->budget, 0, ',', ' ') }} FCFA
                                                </span>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </td>
                                <td class="border border-border px-3 py-2 align-top">
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
                    <h2 class="text-xl font-bold text-accent border-b border-border pb-1 mb-3">
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
                                <div class="p-4 bg-surface rounded-lg border border-border">
                                    <p class="font-medium text-heading">{!! $fieldDef['question_text'] !!}</p>
                                    <p class="text-body mt-1">{!! $value !!}</p>
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
                <h2 class="text-xl font-bold text-accent border-b border-border pb-1 mb-3">Documents associés</h2>
                <ul class="list-disc list-inside text-body">
                    @foreach($project->documents as $doc)
                        <li>{!! $doc->name !!} ({!! $doc->type !!})</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

    {{-- Bouton download --}}
    <div class="text-center mt-6">
        <button id="download-pdf" class="px-6 py-2 bg-accent text-white rounded-md hover:bg-accent-dark">
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
