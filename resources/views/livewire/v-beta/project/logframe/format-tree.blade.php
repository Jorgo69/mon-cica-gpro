{{-- Format Arborescence : hierarchie visuelle indentee --}}
@php $lf = $project->logicalFramework; @endphp

<div class="space-y-6">
    {{-- Objectif General --}}
    <div class="p-5 bg-primary/5 dark:bg-primary/10 rounded-2xl border border-primary/20">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                <x-lucide-target class="w-5 h-5 text-primary" />
            </div>
            <div class="flex-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-primary block mb-1">Objectif General</span>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">{!! $lf->general_objective !!}</p>
                @if($lf->indicators->isNotEmpty())
                    <div class="mt-3 space-y-1">
                        @foreach($lf->indicators as $ind)
                            <div class="flex items-start gap-2 text-xs text-slate-500">
                                <x-lucide-bar-chart-3 class="w-3 h-3 mt-0.5 shrink-0 text-emerald-500" />
                                <span>{{ $ind->description }}@if($ind->verification_source) <em class="text-slate-400">({{ $ind->verification_source }})</em>@endif</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Objectifs Specifiques --}}
    @foreach($lf->specificObjectives as $obj)
        <div class="ml-6 border-l-2 border-accent/30 pl-6">
            <div class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center shrink-0">
                        <span class="text-xs font-black text-accent">{{ $loop->iteration }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="text-[10px] font-black uppercase tracking-widest text-accent block mb-1">Objectif Specifique {{ $loop->iteration }}</span>
                        <p class="text-sm text-slate-700 dark:text-slate-200">{!! $obj->description !!}</p>
                        @if($obj->indicators->isNotEmpty())
                            <div class="mt-2 space-y-1">
                                @foreach($obj->indicators as $ind)
                                    <div class="flex items-start gap-2 text-xs text-slate-500">
                                        <x-lucide-bar-chart-3 class="w-3 h-3 mt-0.5 shrink-0 text-emerald-500" />
                                        <span>{{ $ind->description }}@if($ind->verification_source) <em class="text-slate-400">({{ $ind->verification_source }})</em>@endif</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Resultats --}}
            @foreach($obj->results as $res)
                <div class="ml-6 mt-3 border-l-2 border-slate-200 dark:border-slate-700 pl-4">
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-100 dark:border-slate-700">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 block mb-1">Resultat {{ $loop->parent->iteration }}.{{ $loop->iteration }}</span>
                        <p class="text-sm text-slate-600 dark:text-slate-300">{!! $res->description !!}</p>
                        @if($res->indicators->isNotEmpty())
                            <div class="mt-2 space-y-1">
                                @foreach($res->indicators as $ind)
                                    <div class="flex items-start gap-2 text-xs text-slate-500">
                                        <x-lucide-bar-chart-3 class="w-3 h-3 mt-0.5 shrink-0 text-emerald-500" />
                                        <span>{{ $ind->description }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Activites --}}
                    @if($res->activities->isNotEmpty())
                        <div class="ml-4 mt-2 space-y-1.5">
                            @foreach($res->activities as $act)
                                <div class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                                    <div class="w-1.5 h-1.5 rounded-full bg-accent shrink-0"></div>
                                    <span class="text-xs text-slate-600 dark:text-slate-400 flex-1">{{ $act->description }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $act->responsibleUser->name ?? '' }}</span>
                                    @if($act->status)
                                        <x-ui.badge variant="slate" size="sm">{{ $act->status }}</x-ui.badge>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</div>
