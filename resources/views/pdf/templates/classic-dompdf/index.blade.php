@extends('pdf.layouts.dompdf')

@section('title', 'Projet : ' . $project->title)

@section('content')
    {{-- COVER --}}
    <div class="cover">
        <h4 class="text-muted">CICA-GPRO</h4>
        <div class="divider"></div>
        <h1>{{ $project->title }}</h1>
        @if($project->short_title)
            <p class="subtitle">{{ $project->short_title }}</p>
        @endif
        <p class="subtitle">Code : {{ $project->project_code }}</p>
        <div class="divider"></div>

        <div class="meta">
            @if($project->start_date && $project->end_date)
                <p>Periode : {{ $project->start_date?->format('d/m/Y') ?? '...' }} &rarr; {{ $project->end_date?->format('d/m/Y') ?? '...' }}</p>
            @endif
            @if($project->creator)
                <p>Cree par : {{ $project->creator->name }}</p>
            @endif
            @if($project->projectType)
                <p>Type : {{ $project->projectType->name }}</p>
            @endif
            <p>Statut : <span class="badge badge-primary">{{ $project->status instanceof \App\Enums\ProjectStatus ? $project->status->label() : $project->status }}</span></p>
            <p style="margin-top: 16px;">Genere le {{ now()->format('d/m/Y a H:i') }}</p>
        </div>
    </div>

    <div class="page-break"></div>

    {{-- INFORMATIONS GENERALES --}}
    <div class="section">
        <div class="section-header">Informations Generales</div>
        <div class="section-body">
            <div class="grid-2">
                <div class="col">
                    <div class="info-row">
                        <div class="info-label">Titre</div>
                        <div class="info-value">{{ $project->title }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Code</div>
                        <div class="info-value">{{ $project->project_code }}</div>
                    </div>
                    @if($project->short_title)
                    <div class="info-row">
                        <div class="info-label">Titre abrege</div>
                        <div class="info-value">{{ $project->short_title }}</div>
                    </div>
                    @endif
                </div>
                <div class="col">
                    <div class="info-row">
                        <div class="info-label">Debut</div>
                        <div class="info-value">{{ $project->start_date?->format('d/m/Y') ?? 'Non defini' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Fin</div>
                        <div class="info-value">{{ $project->end_date?->format('d/m/Y') ?? 'Non defini' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Cree par</div>
                        <div class="info-value">{{ $project->creator->name ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DESCRIPTION --}}
    @if($project->description)
    <div class="section">
        <div class="section-header">Description</div>
        <div class="section-body">{!! strip_tags($project->description, '<p><br><strong><em><ul><ol><li>') !!}</div>
    </div>
    @endif

    {{-- CONTEXTE --}}
    @if($project->problem_analysis || $project->strategy || $project->justification)
    <div class="section">
        <div class="section-header">Contexte & Analyse</div>
        <div class="section-body">
            @if($project->justification)
                <h4>Justification</h4>
                <p>{!! strip_tags($project->justification, '<p><br><strong><em>') !!}</p>
            @endif
            @if($project->strategy)
                <h4 style="margin-top: 8px;">Strategie</h4>
                <p>{!! strip_tags($project->strategy, '<p><br><strong><em>') !!}</p>
            @endif
            @if($project->problem_analysis)
                <h4 style="margin-top: 8px;">Analyse du probleme</h4>
                <p>{!! strip_tags($project->problem_analysis, '<p><br><strong><em>') !!}</p>
            @endif
        </div>
    </div>
    @endif

    {{-- CADRE LOGIQUE --}}
    @if($project->logicalFramework)
    @php $lf = $project->logicalFramework; @endphp
    <div class="section">
        <div class="section-header">Cadre Logique</div>
        <div class="section-body">
            <h3>Objectif General</h3>
            <p>{!! strip_tags($lf->general_objective, "<p><br><strong><em><ul><ol><li>") !!}</p>

            {{-- Indicateurs du cadre logique --}}
            @if($lf->indicatorItems->isNotEmpty())
                <h4 style="margin-top: 8px;">Indicateurs</h4>
                @foreach($lf->indicatorItems as $ind)
                <div class="indicator">
                    <strong>{{ $ind->description }}</strong>
                    @if($ind->verification_source)
                        <br><span class="text-muted">Source : {{ $ind->verification_source }}</span>
                    @endif
                </div>
                @endforeach
            @endif

            {{-- Objectifs specifiques --}}
            @if($lf->specificObjectives->isNotEmpty())
                <h3 style="margin-top: 12px;">Objectifs Specifiques</h3>
                @foreach($lf->specificObjectives as $i => $obj)
                    <div style="margin-bottom: 10px; padding-left: 10px; border-left: 3px solid #e74c6f;">
                        <strong>OS{{ $i + 1 }}.</strong> {!! strip_tags($obj->description, "<p><br><strong><em><ul><ol><li>") !!}

                        @if($obj->indicatorItems->isNotEmpty())
                            <div style="margin-top: 4px; margin-left: 10px;">
                                @foreach($obj->indicatorItems as $ind)
                                <div class="indicator">
                                    <span class="text-muted" style="font-size: 9px;">INDICATEUR</span><br>
                                    {{ $ind->description }}
                                    @if($ind->verification_source)
                                        <br><span class="text-muted">Source : {{ $ind->verification_source }}</span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Resultats --}}
                        @if($obj->results->isNotEmpty())
                            @foreach($obj->results as $j => $result)
                            <div style="margin-top: 6px; margin-left: 16px; padding-left: 8px; border-left: 2px solid #0ea5e9;">
                                <strong class="text-accent">R{{ $j + 1 }}.</strong> {!! strip_tags($result->description, "<p><br><strong><em><ul><ol><li>") !!}

                                @if($result->indicatorItems->isNotEmpty())
                                    @foreach($result->indicatorItems as $ind)
                                    <div class="indicator" style="margin-top: 4px;">
                                        <span class="text-muted" style="font-size: 9px;">INDICATEUR</span><br>
                                        {{ $ind->description }}
                                    </div>
                                    @endforeach
                                @endif

                                {{-- Activites --}}
                                @if($result->activities->isNotEmpty())
                                    <table style="margin-top: 6px;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Activite</th>
                                                <th>Responsable</th>
                                                <th>Debut</th>
                                                <th>Fin</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($result->activities as $k => $act)
                                            <tr>
                                                <td>A{{ $k + 1 }}</td>
                                                <td>{!! strip_tags($act->description, "<p><br><strong><em>") !!}</td>
                                                <td>{{ $act->responsibleUser->name ?? 'N/A' }}</td>
                                                <td>{{ $act->start_date?->format('d/m/Y') }}</td>
                                                <td>{{ $act->end_date?->format('d/m/Y') }}</td>
                                                <td>
                                                    @php $statusEnum = $act->status instanceof \App\Enums\ActivityStatus ? $act->status : \App\Enums\ActivityStatus::tryFrom($act->status); @endphp
                                                    <span class="badge badge-slate">{{ $statusEnum ? $statusEnum->label() : $act->status }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    @endif

    {{-- BUDGET --}}
    @if($project->budgets->isNotEmpty())
    <div class="section">
        <div class="section-header">Budget</div>
        <div class="section-body">
            <table>
                <thead>
                    <tr>
                        <th>Poste</th>
                        <th>Responsable</th>
                        <th style="text-align: right;">Montant (FCFA)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->budgets as $budget)
                    <tr>
                        <td>{{ $budget->description ?? 'N/A' }}</td>
                        <td>{{ $budget->responsibleUser->name ?? 'N/A' }}</td>
                        <td style="text-align: right; font-weight: bold;">{{ number_format($budget->total_amount ?? 0, 0, ',', ' ') }}</td>
                    </tr>
                    @endforeach
                    <tr style="border-top: 2px solid #334155;">
                        <td colspan="2" style="font-weight: bold;">TOTAL</td>
                        <td style="text-align: right; font-weight: bold; color: #e74c6f;">{{ number_format($project->budgets->sum('total_amount') ?? 0, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endsection
