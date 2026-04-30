<div>
    @if(count($history) > 0)
        <div class="relative">
            {{-- Ligne verticale --}}
            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-border dark:bg-surface-alt"></div>

            <div class="space-y-4">
                @foreach($history as $entry)
                    @php
                        $percentage = $entry['progress_percentage'] ?? 0;
                        $color = $percentage >= 75 ? 'emerald' : ($percentage >= 25 ? 'amber' : 'rose');
                    @endphp
                    <div class="relative flex gap-4 pl-10">
                        {{-- Point sur la timeline --}}
                        <div class="absolute left-2.5 top-1 w-3 h-3 rounded-full bg-{{ $color }}-500 ring-4 ring-white dark:ring-slate-900"></div>

                        <div class="flex-1 p-4 bg-card rounded-xl border border-border-light shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-lg font-black text-{{ $color }}-600">{{ $percentage }}%</span>
                                    @if($entry['status_update'])
                                        <x-ui.badge variant="slate" size="sm">{{ $entry['status_update'] }}</x-ui.badge>
                                    @endif
                                </div>
                                <span class="text-[10px] text-muted font-medium">
                                    {{ \Carbon\Carbon::parse($entry['date'])->format('d/m/Y') }}
                                </span>
                            </div>

                            @if($entry['justification'])
                                <p class="text-sm text-body mb-2">{{ $entry['justification'] }}</p>
                            @endif

                            @if($entry['evaluation_comment'])
                                <p class="text-xs text-subtle italic border-l-2 border-accent pl-2">{{ $entry['evaluation_comment'] }}</p>
                            @endif

                            @if($entry['performance_score'])
                                <div class="mt-2 flex items-center gap-1">
                                    <x-lucide-star class="w-3 h-3 text-warning" />
                                    <span class="text-xs font-bold text-subtle">Score : {{ $entry['performance_score'] }}/10</span>
                                </div>
                            @endif

                            <p class="text-[10px] text-muted mt-2">
                                Par {{ $entry['creator']['name'] ?? 'Système' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <x-lucide-clock class="w-8 h-8 mx-auto text-body dark:text-subtle mb-2" />
            <p class="text-sm text-muted">Aucun historique de progression</p>
        </div>
    @endif
</div>
