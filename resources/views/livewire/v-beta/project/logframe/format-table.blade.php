{{-- Format Matrice : tableau classique cadre logique (standard ONG) --}}
{{-- Colonnes : Niveau | Description | Indicateurs | Sources | Hypotheses --}}
@php $lf = $project->logicalFramework; @endphp

<div class="overflow-x-auto">
    <table class="w-full text-sm border-collapse">
        <thead>
            <tr class="bg-surface dark:bg-surface-alt/50">
                <th class="px-4 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest border border-border w-28">Niveau</th>
                <th class="px-4 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest border border-border">Description</th>
                <th class="px-4 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest border border-border">Indicateurs</th>
                <th class="px-4 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest border border-border">Sources de verification</th>
                <th class="px-4 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest border border-border">Hypotheses</th>
            </tr>
        </thead>
        <tbody class="text-body">
            {{-- Objectif General --}}
            <tr class="bg-primary/5 dark:bg-primary/10">
                <td class="px-4 py-3 border border-border font-black text-[10px] uppercase tracking-widest text-primary">Objectif General</td>
                <td class="px-4 py-3 border border-border font-semibold">{!! $lf->general_objective !!}</td>
                <td class="px-4 py-3 border border-border align-top">
                    @foreach($lf->indicators as $ind)
                        <p class="text-xs mb-1">{{ $ind->description }}</p>
                    @endforeach
                </td>
                <td class="px-4 py-3 border border-border align-top">
                    @foreach($lf->indicators as $ind)
                        <p class="text-xs mb-1">{{ $ind->verification_source ?? '—' }}</p>
                    @endforeach
                </td>
                <td class="px-4 py-3 border border-border align-top">
                    @foreach($lf->indicators as $ind)
                        <p class="text-xs mb-1">{{ $ind->assumption ?? '—' }}</p>
                    @endforeach
                </td>
            </tr>

            {{-- Objectifs Specifiques --}}
            @foreach($lf->specificObjectives as $obj)
                <tr>
                    <td class="px-4 py-3 border border-border font-bold text-[10px] uppercase tracking-widest text-accent">OS {{ $loop->iteration }}</td>
                    <td class="px-4 py-3 border border-border">{!! $obj->description !!}</td>
                    <td class="px-4 py-3 border border-border align-top">
                        @foreach($obj->indicators as $ind)
                            <p class="text-xs mb-1">{{ $ind->description }}</p>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 border border-border align-top">
                        @foreach($obj->indicators as $ind)
                            <p class="text-xs mb-1">{{ $ind->verification_source ?? '—' }}</p>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 border border-border align-top">
                        @foreach($obj->indicators as $ind)
                            <p class="text-xs mb-1">{{ $ind->assumption ?? '—' }}</p>
                        @endforeach
                    </td>
                </tr>

                {{-- Resultats de cet objectif --}}
                @foreach($obj->results as $res)
                    <tr class="bg-surface/50 dark:bg-surface-alt/30">
                        <td class="px-4 py-3 border border-border text-[10px] uppercase tracking-widest text-muted pl-8">R {{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 border border-border text-subtle">{!! $res->description !!}</td>
                        <td class="px-4 py-3 border border-border align-top">
                            @foreach($res->indicators as $ind)
                                <p class="text-xs mb-1">{{ $ind->description }}</p>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 border border-border align-top">
                            @foreach($res->indicators as $ind)
                                <p class="text-xs mb-1">{{ $ind->verification_source ?? '—' }}</p>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 border border-border align-top">
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
