{{-- Format Matrice : tableau classique cadre logique (standard ONG) --}}
{{-- Colonnes : Niveau | Description | Indicateurs | Sources | Hypotheses --}}
@php $lf = $project->logicalFramework; @endphp

<div class="overflow-x-auto">
    <table class="w-full text-sm border-collapse">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-800/50">
                <th class="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest border border-slate-200 dark:border-slate-700 w-28">Niveau</th>
                <th class="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest border border-slate-200 dark:border-slate-700">Description</th>
                <th class="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest border border-slate-200 dark:border-slate-700">Indicateurs</th>
                <th class="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest border border-slate-200 dark:border-slate-700">Sources de verification</th>
                <th class="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest border border-slate-200 dark:border-slate-700">Hypotheses</th>
            </tr>
        </thead>
        <tbody class="text-slate-600 dark:text-slate-300">
            {{-- Objectif General --}}
            <tr class="bg-primary/5 dark:bg-primary/10">
                <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 font-black text-[10px] uppercase tracking-widest text-primary">Objectif General</td>
                <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 font-semibold">{!! $lf->general_objective !!}</td>
                <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 align-top">
                    @foreach($lf->indicators as $ind)
                        <p class="text-xs mb-1">{{ $ind->description }}</p>
                    @endforeach
                </td>
                <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 align-top">
                    @foreach($lf->indicators as $ind)
                        <p class="text-xs mb-1">{{ $ind->verification_source ?? '—' }}</p>
                    @endforeach
                </td>
                <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 align-top">
                    @foreach($lf->indicators as $ind)
                        <p class="text-xs mb-1">{{ $ind->assumption ?? '—' }}</p>
                    @endforeach
                </td>
            </tr>

            {{-- Objectifs Specifiques --}}
            @foreach($lf->specificObjectives as $obj)
                <tr>
                    <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 font-bold text-[10px] uppercase tracking-widest text-accent">OS {{ $loop->iteration }}</td>
                    <td class="px-4 py-3 border border-slate-200 dark:border-slate-700">{!! $obj->description !!}</td>
                    <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 align-top">
                        @foreach($obj->indicators as $ind)
                            <p class="text-xs mb-1">{{ $ind->description }}</p>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 align-top">
                        @foreach($obj->indicators as $ind)
                            <p class="text-xs mb-1">{{ $ind->verification_source ?? '—' }}</p>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 align-top">
                        @foreach($obj->indicators as $ind)
                            <p class="text-xs mb-1">{{ $ind->assumption ?? '—' }}</p>
                        @endforeach
                    </td>
                </tr>

                {{-- Resultats de cet objectif --}}
                @foreach($obj->results as $res)
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30">
                        <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 text-[10px] uppercase tracking-widest text-slate-400 pl-8">R {{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 text-slate-500">{!! $res->description !!}</td>
                        <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 align-top">
                            @foreach($res->indicators as $ind)
                                <p class="text-xs mb-1">{{ $ind->description }}</p>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 align-top">
                            @foreach($res->indicators as $ind)
                                <p class="text-xs mb-1">{{ $ind->verification_source ?? '—' }}</p>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 border border-slate-200 dark:border-slate-700 align-top">
                            @foreach($res->indicators as $ind)
                                <p class="text-xs mb-1">{{ $ind->assumption ?? '—' }}</p>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
