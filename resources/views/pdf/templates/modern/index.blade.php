<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->title }} - Rapport de Projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        @page { size: A4; margin: 22mm 20mm; }
        .page-break { page-break-after: always; }
        .no-break { page-break-inside: avoid; }
    </style>
</head>
<body class="text-gray-800 text-sm leading-relaxed">

    {{-- ==================== PAGE DE GARDE ==================== --}}
    <div class="flex flex-col justify-center items-center min-h-[85vh] text-center">
        <div class="mb-12">
            <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <p class="text-xs text-gray-400 uppercase tracking-[0.3em] font-semibold mb-8">Rapport de projet</p>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-3 max-w-lg leading-tight">{{ $project->title }}</h1>
        @if($project->short_title)
            <p class="text-base text-gray-500 mb-2">{{ $project->short_title }}</p>
        @endif
        <p class="text-sm text-gray-400 font-mono">{{ $project->project_code }}</p>

        <div class="w-12 h-px bg-gray-300 my-8"></div>

        <div class="text-xs text-gray-400 space-y-1">
            @if($project->start_date && $project->end_date)
                <p>{{ $project->start_date?->format('d/m/Y') }} &mdash; {{ $project->end_date?->format('d/m/Y') }}</p>
            @endif
            @if($project->creator)
                <p>{{ $project->creator->name }}</p>
            @endif
            @if($project->projectType)
                <p>{{ $project->projectType->name }}</p>
            @endif
            <p class="mt-4 text-gray-300">Document genere le {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="page-break"></div>

    {{-- ==================== FICHE PROJET ==================== --}}
    <div class="mb-10">
        <h2 class="text-lg font-bold text-gray-900 border-b-2 border-gray-900 pb-2 mb-6 uppercase tracking-wide">Fiche du projet</h2>

        <table class="w-full text-sm">
            <tbody>
                <tr class="border-b border-gray-100">
                    <td class="py-2.5 pr-4 text-gray-400 font-medium w-40 align-top">Titre</td>
                    <td class="py-2.5 font-semibold text-gray-900">{{ $project->title }}</td>
                </tr>
                <tr class="border-b border-gray-100">
                    <td class="py-2.5 pr-4 text-gray-400 font-medium align-top">Code</td>
                    <td class="py-2.5 font-mono text-gray-700">{{ $project->project_code }}</td>
                </tr>
                @if($project->short_title)
                <tr class="border-b border-gray-100">
                    <td class="py-2.5 pr-4 text-gray-400 font-medium align-top">Titre abrege</td>
                    <td class="py-2.5 text-gray-700">{{ $project->short_title }}</td>
                </tr>
                @endif
                <tr class="border-b border-gray-100">
                    <td class="py-2.5 pr-4 text-gray-400 font-medium align-top">Periode</td>
                    <td class="py-2.5 text-gray-700">{{ $project->start_date?->format('d/m/Y') ?? '...' }} &rarr; {{ $project->end_date?->format('d/m/Y') ?? '...' }}
                        @if($project->start_date && $project->end_date)
                            <span class="text-gray-400 ml-2">({{ (int) $project->start_date->diffInMonths($project->end_date) }} mois)</span>
                        @endif
                    </td>
                </tr>
                @if($project->projectType)
                <tr class="border-b border-gray-100">
                    <td class="py-2.5 pr-4 text-gray-400 font-medium align-top">Type</td>
                    <td class="py-2.5 text-gray-700">{{ $project->projectType->name }}</td>
                </tr>
                @endif
                <tr class="border-b border-gray-100">
                    <td class="py-2.5 pr-4 text-gray-400 font-medium align-top">Statut</td>
                    <td class="py-2.5">
                        <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded">
                            {{ $project->status instanceof \App\Enums\ProjectStatus ? $project->status->label() : $project->status }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="py-2.5 pr-4 text-gray-400 font-medium align-top">Responsable</td>
                    <td class="py-2.5 text-gray-700">{{ $project->creator->name ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ==================== DESCRIPTION ==================== --}}
    @if($project->description)
    <div class="mb-10 no-break">
        <h2 class="text-lg font-bold text-gray-900 border-b-2 border-gray-900 pb-2 mb-4 uppercase tracking-wide">Description</h2>
        <div class="text-sm text-gray-700 leading-relaxed prose prose-sm max-w-none">
            {!! strip_tags($project->description, '<p><br><strong><em><ul><ol><li>') !!}
        </div>
    </div>
    @endif

    {{-- ==================== CONTEXTE ==================== --}}
    @if($project->problem_analysis || $project->strategy || $project->justification || $project->context_description)
    <div class="mb-10">
        <h2 class="text-lg font-bold text-gray-900 border-b-2 border-gray-900 pb-2 mb-4 uppercase tracking-wide">Contexte & Analyse</h2>
        <div class="space-y-5">
            @foreach([
                ['titre' => 'Justification', 'contenu' => $project->justification],
                ['titre' => 'Strategie', 'contenu' => $project->strategy],
                ['titre' => 'Analyse du probleme', 'contenu' => $project->problem_analysis],
                ['titre' => 'Contexte', 'contenu' => $project->context_description],
            ] as $bloc)
                @if(!empty($bloc['contenu']))
                <div class="no-break">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ $bloc['titre'] }}</h3>
                    <div class="text-sm text-gray-700 leading-relaxed bg-gray-50 rounded-lg p-4">
                        {!! strip_tags($bloc['contenu'], '<p><br><strong><em><ul><ol><li>') !!}
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- ==================== CADRE LOGIQUE ==================== --}}
    @if($project->logicalFramework)
    @php $lf = $project->logicalFramework; @endphp
    <div class="page-break"></div>
    <div class="mb-10">
        <h2 class="text-lg font-bold text-gray-900 border-b-2 border-gray-900 pb-2 mb-6 uppercase tracking-wide">Cadre logique</h2>

        {{-- Objectif general --}}
        <div class="mb-6 no-break">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Objectif general</h3>
            <div class="text-sm font-semibold text-gray-900 bg-gray-50 rounded-lg p-4">{!! strip_tags($lf->general_objective, '<p><br><strong><em><ul><ol><li>') !!}</div>
        </div>

        {{-- Indicateurs objectif general --}}
        @if($lf->indicatorItems->isNotEmpty())
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Indicateurs</h4>
            @foreach($lf->indicatorItems as $ind)
            <div class="mb-2 pl-4 border-l-2 border-gray-200 no-break">
                <p class="text-sm text-gray-800">{{ $ind->description }}</p>
                @if($ind->verification_source)
                    <p class="text-xs text-gray-400 mt-0.5">Source : {{ $ind->verification_source }}</p>
                @endif
                @if($ind->assumption)
                    <p class="text-xs text-gray-400">Hypothese : {{ $ind->assumption }}</p>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        {{-- Objectifs specifiques --}}
        @if($lf->specificObjectives->isNotEmpty())
        <div class="space-y-6">
            @foreach($lf->specificObjectives as $i => $obj)
            <div class="no-break">
                <div class="flex items-start gap-3 mb-3">
                    <span class="flex-shrink-0 w-7 h-7 bg-gray-900 text-white rounded flex items-center justify-center text-xs font-bold">{{ $i + 1 }}</span>
                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Objectif specifique {{ $i + 1 }}</h3>
                        <div class="text-sm font-semibold text-gray-900 mt-0.5">{!! strip_tags($obj->description, '<p><br><strong><em><ul><ol><li>') !!}</div>
                    </div>
                </div>

                {{-- Indicateurs de l'objectif --}}
                @if($obj->indicatorItems->isNotEmpty())
                <div class="ml-10 mb-3">
                    @foreach($obj->indicatorItems as $ind)
                    <div class="pl-3 border-l-2 border-gray-200 mb-1.5">
                        <p class="text-xs text-gray-600">{{ $ind->description }}</p>
                        @if($ind->verification_source)
                            <p class="text-xs text-gray-400">Source : {{ $ind->verification_source }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Resultats --}}
                @if($obj->results->isNotEmpty())
                <div class="ml-10 space-y-4">
                    @foreach($obj->results as $j => $result)
                    <div class="no-break">
                        <div class="flex items-start gap-2 mb-2">
                            <span class="flex-shrink-0 text-xs font-bold text-gray-400 bg-gray-100 rounded px-1.5 py-0.5">R{{ $j + 1 }}</span>
                            <div class="text-sm text-gray-800">{!! strip_tags($result->description, '<p><br><strong><em><ul><ol><li>') !!}</div>
                        </div>

                        {{-- Indicateurs du resultat --}}
                        @if($result->indicatorItems->isNotEmpty())
                        <div class="ml-8 mb-2">
                            @foreach($result->indicatorItems as $ind)
                            <div class="pl-3 border-l border-gray-200 mb-1">
                                <p class="text-xs text-gray-500">{{ $ind->description }}</p>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Activites --}}
                        @if($result->activities->isNotEmpty())
                        <div class="ml-8">
                            <table class="w-full text-xs border-collapse">
                                <thead>
                                    <tr class="text-left">
                                        <th class="py-1.5 pr-2 text-gray-400 font-semibold border-b border-gray-200 w-8">#</th>
                                        <th class="py-1.5 pr-2 text-gray-400 font-semibold border-b border-gray-200">Activite</th>
                                        <th class="py-1.5 pr-2 text-gray-400 font-semibold border-b border-gray-200 w-28">Responsable</th>
                                        <th class="py-1.5 pr-2 text-gray-400 font-semibold border-b border-gray-200 w-20">Debut</th>
                                        <th class="py-1.5 pr-2 text-gray-400 font-semibold border-b border-gray-200 w-20">Fin</th>
                                        <th class="py-1.5 text-gray-400 font-semibold border-b border-gray-200 w-20">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($result->activities as $k => $act)
                                    <tr>
                                        <td class="py-1.5 pr-2 text-gray-400 border-b border-gray-50">A{{ $k + 1 }}</td>
                                        <td class="py-1.5 pr-2 text-gray-800 font-medium border-b border-gray-50">{!! strip_tags($act->description, '<p><br><strong><em>') !!}</td>
                                        <td class="py-1.5 pr-2 text-gray-600 border-b border-gray-50">{{ $act->responsibleUser->name ?? 'N/A' }}</td>
                                        <td class="py-1.5 pr-2 text-gray-500 border-b border-gray-50">{{ $act->start_date?->format('d/m') }}</td>
                                        <td class="py-1.5 pr-2 text-gray-500 border-b border-gray-50">{{ $act->end_date?->format('d/m') }}</td>
                                        <td class="py-1.5 border-b border-gray-50">
                                            @php $statusEnum = $act->status instanceof \App\Enums\ActivityStatus ? $act->status : \App\Enums\ActivityStatus::tryFrom($act->status ?? ''); @endphp
                                            <span class="inline-block px-1.5 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-semibold rounded">
                                                {{ $statusEnum ? $statusEnum->label() : ($act->status ?? 'N/A') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    {{-- ==================== BUDGET ==================== --}}
    @if($project->budgets->isNotEmpty())
    <div class="mb-10 no-break">
        <h2 class="text-lg font-bold text-gray-900 border-b-2 border-gray-900 pb-2 mb-4 uppercase tracking-wide">Budget previsionnel</h2>
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="text-left">
                    <th class="py-2 pr-4 text-gray-400 font-semibold border-b-2 border-gray-200">Poste budgetaire</th>
                    <th class="py-2 pr-4 text-gray-400 font-semibold border-b-2 border-gray-200 w-32">Responsable</th>
                    <th class="py-2 text-gray-400 font-semibold border-b-2 border-gray-200 text-right w-32">Montant (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($project->budgets as $budget)
                <tr>
                    <td class="py-2 pr-4 text-gray-800 border-b border-gray-100">{{ $budget->description ?? 'N/A' }}</td>
                    <td class="py-2 pr-4 text-gray-600 border-b border-gray-100">{{ $budget->responsibleUser->name ?? 'N/A' }}</td>
                    <td class="py-2 text-gray-800 font-semibold border-b border-gray-100 text-right">{{ number_format($budget->total_amount ?? 0, 0, ',', ' ') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="py-3 font-bold text-gray-900 border-t-2 border-gray-900">TOTAL</td>
                    <td class="py-3 font-bold text-gray-900 border-t-2 border-gray-900 text-right">{{ number_format($project->budgets->sum('total_amount') ?? 0, 0, ',', ' ') }} FCFA</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    {{-- ==================== PIED DE PAGE ==================== --}}
    <div class="mt-16 pt-4 border-t border-gray-200 text-center">
        <p class="text-[10px] text-gray-300">Document genere par CICA-GPRO &bull; {{ now()->format('d/m/Y H:i') }} &bull; Ce document est confidentiel</p>
    </div>

</body>
</html>
