{{-- Format Fiches : sections separees avec cards (format existant ameliore) --}}
@php $lf = $project->logicalFramework; @endphp

{{-- Objectif General --}}
<x-ui.section title="Objectif General" icon="target">
    <div class="overflow-x-auto -mx-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 dark:border-slate-800">
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-48">Champs</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Valeur</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50 text-slate-600 dark:text-slate-300">
                <tr><td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-200">Description</td><td class="px-6 py-3 text-justify">{!! $lf->general_objective !!}</td></tr>
                <tr>
                    <td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-200 align-top">Indicateurs</td>
                    <td class="px-6 py-3">
                        @forelse($lf->indicators as $indicator)
                            <div class="mb-3 last:mb-0 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ $indicator->description }}</p>
                                @if($indicator->verification_source)
                                    <p class="text-xs text-slate-500 mt-1"><span class="font-semibold">Source :</span> {{ $indicator->verification_source }}</p>
                                @endif
                                @if($indicator->assumption)
                                    <p class="text-xs text-slate-500 mt-0.5"><span class="font-semibold">Hypothese :</span> {{ $indicator->assumption }}</p>
                                @endif
                            </div>
                        @empty
                            <span class="text-slate-400 italic text-sm">Aucun indicateur defini</span>
                        @endforelse
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</x-ui.section>

{{-- Objectifs Specifiques --}}
@if($lf->specificObjectives->isNotEmpty())
    <x-ui.section title="Objectifs Specifiques" icon="list-ordered">
        <div class="overflow-x-auto -mx-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Champs</th>
                        @foreach($lf->specificObjectives as $obj)
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Objectif {{ $loop->iteration }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50 text-slate-600 dark:text-slate-300">
                    <tr><td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-200">Description</td>@foreach($lf->specificObjectives as $obj)<td class="px-6 py-3">{!! $obj->description !!}</td>@endforeach</tr>
                    <tr>
                        <td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-200 align-top">Indicateurs</td>
                        @foreach($lf->specificObjectives as $obj)
                            <td class="px-6 py-3">
                                @forelse($obj->indicators as $indicator)
                                    <div class="mb-2 last:mb-0 p-2 bg-slate-50 dark:bg-slate-800/50 rounded-lg text-xs">
                                        <p class="font-medium text-slate-700 dark:text-slate-200">{{ $indicator->description }}</p>
                                        @if($indicator->verification_source)
                                            <p class="text-slate-500 mt-0.5"><span class="font-semibold">Source :</span> {{ $indicator->verification_source }}</p>
                                        @endif
                                        @if($indicator->assumption)
                                            <p class="text-slate-500 mt-0.5"><span class="font-semibold">Hypothese :</span> {{ $indicator->assumption }}</p>
                                        @endif
                                    </div>
                                @empty
                                    <span class="text-slate-400 italic">—</span>
                                @endforelse
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </x-ui.section>
@endif

{{-- Resultats Attendus --}}
@php
    $results = collect();
    foreach($lf->specificObjectives as $obj){
        $results = $results->merge($obj->results);
    }
@endphp
@if($results->isNotEmpty())
    <x-ui.section title="Resultats Attendus" icon="check-square">
        <div class="overflow-x-auto -mx-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Champs</th>
                        @foreach($results as $result)
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Resultat {{ $loop->iteration }}</th>
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

{{-- Activites --}}
@php $activities = $project->getAllActivities(); @endphp
@if($activities->isNotEmpty())
    <x-ui.section title="Liste des Activites" icon="list-checks">
        <div class="overflow-x-auto -mx-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Description</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Responsable</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Debut</th>
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
