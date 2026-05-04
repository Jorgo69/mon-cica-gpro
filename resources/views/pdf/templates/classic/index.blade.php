@extends('pdf.layouts.report')

@section('title', 'Projet : ' . $project->title)

@section('content')
    <div class="p-12">
        {{-- En-tête officiel --}}
        <div class="flex justify-between items-start mb-12 border-b-2 border-surface pb-8">
            <div class="w-2/3">
                <h1 class="text-2xl font-bold uppercase mb-2">{{ $project->title }}</h1>
                <p class="text-sm text-subtle italic">Document de référence technique</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black uppercase text-muted">Code Identification</p>
                <p class="text-lg font-bold">{{ $project->project_code }}</p>
            </div>
        </div>

        {{-- Tableau des données de base --}}
        <div class="mb-10">
            <h3 class="text-xs font-black uppercase tracking-widest bg-surface-alt p-2 mb-4">1. Identité du Projet</h3>
            <table class="w-full text-sm border-collapse border border-border">
                <tr>
                    <td class="border border-border p-3 bg-surface font-bold w-1/3">Statut</td>
                    <td class="border border-border p-3">{{ $project->status?->label() ?? 'Non défini' }}</td>
                </tr>
                <tr>
                    <td class="border border-border p-3 bg-surface font-bold">Type</td>
                    <td class="border border-border p-3">{{ $project->projectType?->name ?? 'Standard' }}</td>
                </tr>
                <tr>
                    <td class="border border-border p-3 bg-surface font-bold">Période d'exécution</td>
                    <td class="border border-border p-3">{{ $project->start_date?->format('d/m/Y') }} au {{ $project->end_date?->format('d/m/Y') }}</td>
                </tr>
            </table>
        </div>

        {{-- Cadre Logique --}}
        <div class="mb-10">
            <h3 class="text-xs font-black uppercase tracking-widest bg-surface-alt p-2 mb-4">2. Cadre Logique</h3>
            @if($project->logicalFramework && $project->logicalFramework->specificObjectives->count())
                <table class="w-full text-xs border-collapse border border-border">
                    <thead class="bg-surface">
                        <tr>
                            <th class="border border-border p-2 text-left">Objectif Spécifique</th>
                            <th class="border border-border p-2 text-left">Résultats & Activités</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->logicalFramework->specificObjectives as $obj)
                            <tr>
                                <td class="border border-border p-3 align-top font-bold">
                                    {{ $obj->description }}
                                    @if($obj->indicatorItems->isNotEmpty())
                                        <div class="mt-2 font-normal text-subtle">
                                            @foreach($obj->indicatorItems as $indicator)
                                                <p class="text-xs mb-1">• {{ $indicator->description }}@if($indicator->verification_source) <em>({{ $indicator->verification_source }})</em>@endif</p>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="border border-border p-3 align-top">
                                    @foreach($obj->results as $res)
                                        <div class="mb-3">
                                            <p class="font-bold underline mb-1">Résultat : {{ $res->description }}</p>
                                            @if($res->indicatorItems->isNotEmpty())
                                                <div class="mb-2 text-subtle">
                                                    @foreach($res->indicatorItems as $indicator)
                                                        <p class="text-xs mb-0.5">↳ {{ $indicator->description }}@if($indicator->verification_source) <em>({{ $indicator->verification_source }})</em>@endif</p>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <ul class="list-disc list-inside">
                                                @foreach($res->activities as $act)
                                                    <li>{{ $act->description }} ({{ number_format($act->budget, 0, ',', ' ') }} FCFA)</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-sm italic text-subtle">Aucun cadre logique disponible.</p>
            @endif
        </div>

        {{-- Autres informations --}}
        @if($dynamicFormFields)
            <div class="page-break"></div>
            <h3 class="text-xs font-black uppercase tracking-widest bg-surface-alt p-2 mb-4">3. Informations Complémentaires</h3>
            @foreach($dynamicFormFields as $section => $fields)
                <div class="mb-6">
                    <h4 class="text-sm font-bold border-b border-border mb-3">{{ ucfirst($section) }}</h4>
                    <div class="space-y-4">
                        @php $fieldValues = $project->general_objectives ?? []; @endphp
                        @foreach($fields as $fieldDef)
                            @php
                                $value = $fieldValues[$fieldDef['field_name']] ?? null;
                                if (is_array($value)) $value = implode(', ', $value);
                            @endphp
                            @if($value)
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-muted">{{ $fieldDef['question_text'] }}</p>
                                    <p class="text-sm leading-relaxed">{{ $value }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
