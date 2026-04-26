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
                <p class="text-sm font-semibold text-heading">{!! $lf->general_objective !!}</p>
                @if($lf->indicators->isNotEmpty())
                    <div class="mt-3 space-y-1">
                        @foreach($lf->indicators as $ind)
                            <div class="flex items-start gap-2 text-xs text-subtle">
                                <x-lucide-bar-chart-3 class="w-3 h-3 mt-0.5 shrink-0 text-success" />
                                <span>{{ $ind->description }}@if($ind->verification_source) <em class="text-muted">({{ $ind->verification_source }})</em>@endif</span>
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
            <div class="p-4 bg-card rounded-xl border border-border shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center shrink-0">
                        <span class="text-xs font-black text-accent">{{ $loop->iteration }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="text-[10px] font-black uppercase tracking-widest text-accent block mb-1">Objectif Specifique {{ $loop->iteration }}</span>
                        <p class="text-sm text-body">{!! $obj->description !!}</p>
                        @if($obj->indicators->isNotEmpty())
                            <div class="mt-2 space-y-1">
                                @foreach($obj->indicators as $ind)
                                    <div class="flex items-start gap-2 text-xs text-subtle">
                                        <x-lucide-bar-chart-3 class="w-3 h-3 mt-0.5 shrink-0 text-success" />
                                        <span>{{ $ind->description }}@if($ind->verification_source) <em class="text-muted">({{ $ind->verification_source }})</em>@endif</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Resultats --}}
            @foreach($obj->results as $res)
                <div class="ml-6 mt-3 border-l-2 border-border pl-4">
                    <div class="p-3 bg-surface dark:bg-surface-alt/50 rounded-lg border border-border-light">
                        <span class="text-[9px] font-black uppercase tracking-widest text-muted block mb-1">Resultat {{ $loop->parent->iteration }}.{{ $loop->iteration }}</span>
                        <p class="text-sm text-body">{!! $res->description !!}</p>
                        @if($res->indicators->isNotEmpty())
                            <div class="mt-2 space-y-1">
                                @foreach($res->indicators as $ind)
                                    <div class="flex items-start gap-2 text-xs text-subtle">
                                        <x-lucide-bar-chart-3 class="w-3 h-3 mt-0.5 shrink-0 text-success" />
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
                                <div class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-surface-alt dark:hover:bg-surface-alt/50 transition-colors">
                                    <div class="w-1.5 h-1.5 rounded-full bg-accent shrink-0"></div>
                                    <span class="text-xs text-subtle flex-1">{{ $act->description }}</span>
                                    <span class="text-[10px] text-muted">{{ $act->responsibleUser->name ?? '' }}</span>
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
