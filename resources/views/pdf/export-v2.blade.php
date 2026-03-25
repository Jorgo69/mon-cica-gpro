{{-- resources/views/v_beta/pdf/index.blade.php --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche de projet - {{ $project->title }}</title>
</head>
<body class="font-sans text-gray-800">

    {{-- PAGE DE GARDE --}}
    <div class="h-screen flex flex-col justify-center items-center text-center bg-blue-50">
        {{-- Logo placeholder --}}
        <div class="mb-8">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="h-20 mx-auto">
        </div>

        <h1 class="text-4xl font-bold text-blue-700 mb-4">
            FICHE DE PROJET
        </h1>

        <p class="text-lg text-gray-600 mb-8">
            Document de référence – {{ now()->format('d/m/Y') }}
        </p>

        <div class="text-left bg-white shadow-lg rounded-lg p-8 w-2/3 border border-gray-200">
            <p><strong class="text-gray-700">Titre du projet :</strong> {{ $project->title }}</p>
            <p><strong class="text-gray-700">Code :</strong> {{ $project->project_code }}</p>
            <p><strong class="text-gray-700">Type :</strong> {{ $project->projectType->name ?? '—' }}</p>
            <p><strong class="text-gray-700">Promoteur :</strong> {{ $project->creator->name ?? '—' }}</p>
        </div>

        {{-- Forcer saut de page --}}
        <div style="page-break-after: always;"></div>
    </div>

    {{-- CONTENU PRINCIPAL --}}
    <div class="px-12 py-10">
        {{-- CONTEXTE --}}
        <h2 class="text-2xl font-semibold text-blue-700 mb-2">Contexte</h2>
        <div class="mb-6 prose max-w-none">{!! $project->projectContext->context_description ?? '—' !!}</div>

        {{-- ANALYSE DU PROBLÈME --}}
        <h2 class="text-2xl font-semibold text-blue-700 mb-2">Analyse du problème</h2>
        <div class="mb-6 prose max-w-none">{!! $project->problem_analysis ?? '—' !!}</div>

        {{-- STRATEGIE --}}
        <h2 class="text-2xl font-semibold text-blue-700 mb-2">Stratégie</h2>
        <div class="mb-6 prose max-w-none">{!! $project->strategy ?? '—' !!}</div>

        {{-- JUSTIFICATION --}}
        <h2 class="text-2xl font-semibold text-blue-700 mb-2">Justification</h2>
        <div class="mb-6 prose max-w-none">{!! $project->justification ?? '—' !!}</div>

        {{-- OBJECTIFS --}}
        <h2 class="text-2xl font-semibold text-blue-700 mb-4">Objectifs spécifiques</h2>
        @forelse($project->logicalFramework->specificObjectives ?? [] as $objective)
            <div class="mb-6 p-4 border border-gray-300 rounded-lg bg-gray-50">
                <p><strong>Description :</strong> {{ $objective->specific_obj_desc }}</p>

                {{-- Résultats --}}
                @if($objective->results->count())
                    <h3 class="text-lg font-semibold mt-4 text-blue-600">Résultats attendus</h3>
                    <ul class="list-disc pl-6">
                        @foreach($objective->results as $result)
                            <li class="mb-2">
                                <p><strong>{{ $result->result_desc }}</strong></p>

                                {{-- Activités --}}
                                @if($result->activities->count())
                                    <h4 class="text-md font-medium mt-2">Activités</h4>
                                    <ul class="list-decimal pl-6">
                                        @foreach($result->activities as $activity)
                                            <li>
                                                {{ $activity->activity_desc }}
                                                <span class="text-gray-500 text-sm">
                                                    (Budget : {{ number_format($activity->budget, 0, ',', ' ') }} FCFA)
                                                </span>

                                                {{-- Sous-activités --}}
                                                @if($activity->subActivities->count())
                                                    <ul class="list-disc pl-6 text-sm text-gray-700">
                                                        @foreach($activity->subActivities as $sub)
                                                            <li>{{ $sub->sub_activity_desc }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @empty
            <p class="text-gray-500">Aucun objectif défini.</p>
        @endforelse

        {{-- DOCUMENTS --}}
        <h2 class="text-2xl font-semibold text-blue-700 mb-4">Documents liés</h2>
        @if($project->documents->count())
            <ul class="list-disc pl-6">
                @foreach($project->documents as $doc)
                    <li>{{ $doc->file_name }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">Aucun document disponible.</p>
        @endif
    </div>
</body>
</html>
