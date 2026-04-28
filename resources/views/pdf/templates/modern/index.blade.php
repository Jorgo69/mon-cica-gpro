@extends('pdf.layouts.report')

@section('title', 'Rapport de Projet - ' . $project->title)

@section('content')
    {{-- Page de Garde --}}
    <div class="h-screen flex flex-col justify-center items-center header-bg text-white px-20 text-center relative overflow-hidden">
        <div class="z-10">
            <div class="w-24 h-24 rounded-3xl bg-white/10 flex items-center justify-center mx-auto mb-8 backdrop-blur-md border border-white/20">
                <svg class="w-12 h-12 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h4 class="text-accent font-black uppercase tracking-[0.3em] text-sm mb-4">Rapport Officiel</h4>
            <h1 class="text-5xl font-extrabold mb-6 leading-tight">{{ $project->title }}</h1>
            <div class="h-1 w-24 bg-accent mx-auto mb-8 rounded-full"></div>
            <p class="text-xl text-body font-medium mb-12">Code Projet : {{ $project->project_code }}</p>
            
            <div class="grid grid-cols-2 gap-12 text-left bg-white/5 p-8 rounded-3xl backdrop-blur-sm border border-white/10">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-muted block mb-1">Créé par</span>
                    <span class="text-lg font-bold">{{ $project->creator?->name ?? 'Système' }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-muted block mb-1">Date d'édition</span>
                    <span class="text-lg font-bold">{{ now()->translatedFormat('d F Y') }}</span>
                </div>
            </div>
        </div>
        
        {{-- Décoration --}}
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-accent opacity-10 rounded-full blur-3xl"></div>
    </div>

    <div class="page-break"></div>

    {{-- Synthèse du Projet --}}
    <div class="p-12">
        <div class="flex items-center gap-4 mb-12">
            <div class="w-1.5 h-10 bg-accent rounded-full"></div>
            <h2 class="text-3xl font-extrabold tracking-tight">Synthèse du Projet</h2>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-12">
            <div class="card bg-surface border-none">
                <span class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Statut Actuel</span>
                <span class="px-3 py-1 bg-white rounded-full text-xs font-bold shadow-sm border border-border">{{ $project->status?->label() ?? 'N/A' }}</span>
            </div>
            <div class="card bg-surface border-none">
                <span class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Type de Projet</span>
                <span class="text-sm font-bold">{{ $project->projectType?->name ?? 'Standard' }}</span>
            </div>
            <div class="card bg-surface border-none">
                <span class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Budget Total</span>
                <span class="text-sm font-bold text-primary">{{ number_format($project->budgets->sum('total_amount'), 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        <div class="mb-12">
            <h3 class="text-sm font-black uppercase tracking-widest text-muted mb-4">Objectifs Généraux</h3>
            <div class="prose prose-slate max-w-none text-subtle leading-relaxed italic border-l-4 border-border pl-6">
                {{ $project->description ?? 'Aucune description disponible.' }}
            </div>
        </div>

        {{-- CADRE LOGIQUE --}}
        <h3 class="text-sm font-black uppercase tracking-widest text-muted mb-6 font-bold">Cadre Logique & Structure</h3>
        @if($project->logicalFramework && $project->logicalFramework->specificObjectives->count())
            <div class="space-y-6 mb-12">
                @foreach($project->logicalFramework->specificObjectives as $obj)
                    <div class="card bg-white">
                        <div class="flex items-start gap-4 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-surface-alt flex items-center justify-center font-bold text-xs text-subtle shrink-0">OS</span>
                            <div>
                                <p class="text-base font-bold text-heading">{{ $obj->description }}</p>
                                @if($obj->indicatorItems->isNotEmpty())
                                    <div class="mt-2 space-y-1">
                                        @foreach($obj->indicatorItems as $indicator)
                                            <div class="text-xs text-subtle pl-2 border-l-2 border-emerald-200">
                                                <span class="font-semibold">{{ $indicator->description }}</span>
                                                @if($indicator->verification_source) <span class="text-muted">· Source : {{ $indicator->verification_source }}</span> @endif
                                                @if($indicator->assumption) <span class="text-muted">· Hyp. : {{ $indicator->assumption }}</span> @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="ml-12 space-y-4">
                            @foreach($obj->results as $res)
                                <div class="p-4 bg-surface rounded-2xl border border-border text-subtle text-sm">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-accent block mb-1">Résultat</span>
                                    <span class="italic">{{ $res->description }}</span>
                                    @if($res->indicatorItems->isNotEmpty())
                                        <div class="mt-2 space-y-1 not-italic">
                                            @foreach($res->indicatorItems as $indicator)
                                                <div class="text-xs pl-2 border-l-2 border-emerald-200">
                                                    <span class="font-semibold">{{ $indicator->description }}</span>
                                                    @if($indicator->verification_source) <span class="text-muted">· Source : {{ $indicator->verification_source }}</span> @endif
                                                    @if($indicator->assumption) <span class="text-muted">· Hyp. : {{ $indicator->assumption }}</span> @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-1 gap-3 mt-3">
                                    @foreach($res->activities as $act)
                                        <div class="flex items-center justify-between p-3 pl-4 bg-white rounded-xl border border-border shadow-sm">
                                            <div class="flex items-center gap-3">
                                                <div class="w-2 h-2 rounded-full bg-accent"></div>
                                                <span class="text-xs font-semibold text-body">{{ $act->description }}</span>
                                            </div>
                                            <span class="text-[10px] font-bold text-muted">{{ number_format($act->budget, 0, ',', ' ') }} FCFA</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center bg-surface rounded-3xl border border-dashed border-border mb-12">
                <p class="text-sm text-muted font-medium">Aucun cadre logique défini.</p>
            </div>
        @endif

        {{-- CHAMPS DYNAMIQUES --}}
        @if($dynamicFormFields)
            <div class="page-break"></div>
            <div class="py-12">
                <div class="flex items-center gap-4 mb-12">
                    <div class="w-1.5 h-10 bg-primary rounded-full"></div>
                    <h2 class="text-3xl font-extrabold tracking-tight">Informations Complémentaires</h2>
                </div>

                @foreach($dynamicFormFields as $section => $fields)
                    <div class="mb-10">
                        <h3 class="text-sm font-black uppercase tracking-widest text-muted mb-6 border-b border-border pb-2">{{ ucfirst($section) }}</h3>
                        <div class="space-y-4">
                            @foreach($fields as $fieldDef)
                                @php
                                    $targetField = $fieldDef['target_project_field'];
                                    $value = null;
                                    if (isset($project->$targetField)) {
                                        // On supporte le format délimité et le format JSON
                                        if (is_array($project->$targetField)) {
                                            $value = $project->$targetField[$fieldDef['question_text']] ?? null;
                                        } else {
                                            $pattern = '/' . preg_quote($fieldDef['delimiter_start'], '/') . '(.*?)' . preg_quote($fieldDef['delimiter_end'], '/') . '/s';
                                            if (preg_match($pattern, $project->$targetField, $matches)) {
                                                $value = $matches[1];
                                            }
                                        }
                                    }
                                @endphp
                                @if($value)
                                    <div class="p-6 bg-surface rounded-2xl border border-border">
                                        <p class="text-[10px] font-black tracking-widest text-muted uppercase mb-2">{{ $fieldDef['question_text'] }}</p>
                                        <div class="text-sm text-body leading-relaxed prose prose-sm max-w-none">
                                            {!! nl2br(e($value)) !!}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
