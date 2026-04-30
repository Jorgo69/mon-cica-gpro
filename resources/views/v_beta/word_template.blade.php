<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Fiche de projet - {{ $project->title }}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        /* Style simple, supportable par la plupart des générateurs docx */
        body { font-family: Arial, sans-serif; color: #222; font-size: 12pt; line-height:1.4; }
        .center { text-align:center; }
        h1 { color: #1e60c8; font-size: 20pt; margin-bottom: 4px; }
        h2 { color: #1e60c8; font-size: 14pt; margin-top: 18px; margin-bottom: 6px; }
        .meta { margin-bottom: 12px; }
        .section { margin-bottom: 10px; }
        table { width:100%; border-collapse:collapse; margin-top:6px; }
        th, td { border:1px solid #999; padding:6px; vertical-align:top; text-align:left; }
    </style>
</head>
<body>
    <div class="center">
        <h1>FICHE DE PROJET</h1>
        <div class="meta">Document généré le {{ now()->format('d/m/Y') }}</div>
    </div>

    <div class="section">
        <strong>Titre du projet :</strong> {{ $project->title ?? '' }}<br>
        <strong>Code :</strong> {{ $project->project_code ?? '' }}<br>
        <strong>Type :</strong> {{ $project->projectType->name ?? '' }}<br>
        <strong>Promoteur :</strong> {{ $project->creator->name ?? '' }}
    </div>

    <div class="section">
        <h2>Contexte</h2>
        {{-- Contenu HTML enregistré par Summernote : on veut l'envoyer tel quel --}}
        {!! $project->projectContext->context_description ?? '' !!}
    </div>

    <div class="section">
        <h2>Analyse du problème</h2>
        {!! $project->problem_analysis ?? '' !!}
    </div>

    <div class="section">
        <h2>Stratégie</h2>
        {!! $project->strategy ?? '' !!}
    </div>

    <div class="section">
        <h2>Justification</h2>
        {!! $project->justification ?? '' !!}
    </div>

    {{-- Cadre logique : objectifs -> résultats -> activités --}}
    @if($project->logicalFramework && $project->logicalFramework->specificObjectives->count())
        <div class="section">
            <h2>Objectifs spécifiques</h2>
            @foreach($project->logicalFramework->specificObjectives as $objective)
                <div style="margin-bottom:8px;">
                    <strong>Objectif :</strong> {{ $objective->description ?? '' }}<br>
                    @if($objective->results->count())
                        <strong>Résultats :</strong>
                        <ul>
                            @foreach($objective->results as $result)
                                <li>
                                    {!! $result->description ?? '' !!}
                                    @if($result->activities->count())
                                        <ul>
                                            @foreach($result->activities as $activity)
                                                <li>
                                                    {!! $activity->description ?? '' !!}
                                                    <div style="font-size:10pt;color:#444;">
                                                        Responsable: {{ $activity->responsibleUser->name ?? '—' }}
                                                        — Budget: {{ isset($activity->budget) ? number_format($activity->budget,0,',',' ') : '—' }}
                                                    </div>
                                                    @if(isset($activity->subActivities) && $activity->subActivities->count())
                                                        <ul>
                                                            @foreach($activity->subActivities as $sub)
                                                                <li>{!! $sub->description ?? '' !!}</li>
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
            @endforeach
        </div>
    @endif

    {{-- Documents --}}
    @if($project->documents->count())
        <div class="section">
            <h2>Documents</h2>
            <ul>
                @foreach($project->documents as $doc)
                    <li>{{ $doc->file_name ?? $doc->name ?? 'document' }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>
</html>
