<div class="space-y-6">
    {{-- Resume global --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-card rounded-xl border border-border">
            <span class="text-[10px] font-black text-muted uppercase tracking-widest block mb-1">Progression reelle</span>
            <div class="flex items-end gap-2">
                <span class="text-2xl font-black text-accent">{{ $projectProgress }}%</span>
            </div>
            <div class="mt-2 w-full bg-surface-alt dark:bg-surface-alt rounded-full h-2">
                <div class="bg-accent h-2 rounded-full transition-all" style="width: {{ $projectProgress }}%"></div>
            </div>
        </div>
        <div class="p-4 bg-card rounded-xl border border-border">
            <span class="text-[10px] font-black text-muted uppercase tracking-widest block mb-1">Progression planifiee</span>
            <div class="flex items-end gap-2">
                <span class="text-2xl font-black text-primary">{{ $projectPlanned }}%</span>
            </div>
            <div class="mt-2 w-full bg-surface-alt dark:bg-surface-alt rounded-full h-2">
                <div class="bg-primary h-2 rounded-full transition-all" style="width: {{ $projectPlanned }}%"></div>
            </div>
        </div>
        <div class="p-4 bg-card rounded-xl border border-border">
            @php $globalGap = $projectProgress - $projectPlanned; @endphp
            <span class="text-[10px] font-black text-muted uppercase tracking-widest block mb-1">Ecart</span>
            <span class="text-2xl font-black {{ $globalGap >= 0 ? 'text-success' : 'text-rose-600' }}">
                {{ $globalGap >= 0 ? '+' : '' }}{{ $globalGap }}%
            </span>
            <p class="text-xs text-subtle mt-1">
                {{ $globalGap >= 0 ? 'En avance ou dans les temps' : 'En retard sur le planning' }}
            </p>
        </div>
    </div>

    {{-- Tableau detaille par activite --}}
    @if(count($activities) > 0)
        <div class="bg-card rounded-xl border border-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-surface-alt/50 bg-surface-alt/50">
                        <tr class="border-b border-border-light bg-surface dark:bg-surface-alt/50">
                            <th class="px-4 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">Activite</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">Responsable</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">Statut</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest w-48">Planifie vs Reel</th>
                            <th class="px-4 py-3 text-center text-[10px] font-black text-muted uppercase tracking-widest">Ecart</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light dark:divide-surface-alt/50">
                        @foreach($activities as $act)
                            <tr wire:key="act-{{ $act['id'] ?? $loop->index }}" class="hover:bg-surface/50 dark:hover:bg-surface-alt/30 transition-colors">
                                <td class="px-4 py-3 text-heading max-w-xs truncate">{{ $act['description'] }}</td>
                                <td class="px-4 py-3 text-subtle text-xs">{{ $act['responsible'] }}</td>
                                <td class="px-4 py-3">
                                    <x-ui.badge variant="slate" size="sm">{{ $act['status'] ?? 'N/A' }}</x-ui.badge>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="space-y-1.5">
                                        {{-- Barre planifiee --}}
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] text-muted w-10">Plan.</span>
                                            <div class="flex-1 bg-surface-alt dark:bg-surface-alt rounded-full h-1.5">
                                                <div class="bg-primary/60 h-1.5 rounded-full" style="width: {{ $act['planned'] }}%"></div>
                                            </div>
                                            <span class="text-[10px] font-bold text-muted w-8 text-right">{{ $act['planned'] }}%</span>
                                        </div>
                                        {{-- Barre reelle --}}
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] text-muted w-10">Reel</span>
                                            <div class="flex-1 bg-surface-alt dark:bg-surface-alt rounded-full h-1.5">
                                                <div class="bg-accent h-1.5 rounded-full" style="width: {{ $act['real'] }}%"></div>
                                            </div>
                                            <span class="text-[10px] font-bold text-subtle w-8 text-right">{{ $act['real'] }}%</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs font-bold {{ $act['gap'] >= 0 ? 'text-success' : 'text-rose-600' }}">
                                        {{ $act['gap'] >= 0 ? '+' : '' }}{{ $act['gap'] }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-10">
            <x-lucide-bar-chart-3 class="w-10 h-10 mx-auto text-body dark:text-subtle mb-3" />
            <p class="text-sm text-muted">Aucune activite pour comparer</p>
        </div>
    @endif
</div>
