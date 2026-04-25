<div class="space-y-6">
    {{-- Resume global --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Progression reelle</span>
            <div class="flex items-end gap-2">
                <span class="text-2xl font-black text-accent">{{ $projectProgress }}%</span>
            </div>
            <div class="mt-2 w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2">
                <div class="bg-accent h-2 rounded-full transition-all" style="width: {{ $projectProgress }}%"></div>
            </div>
        </div>
        <div class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Progression planifiee</span>
            <div class="flex items-end gap-2">
                <span class="text-2xl font-black text-primary">{{ $projectPlanned }}%</span>
            </div>
            <div class="mt-2 w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2">
                <div class="bg-primary h-2 rounded-full transition-all" style="width: {{ $projectPlanned }}%"></div>
            </div>
        </div>
        <div class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
            @php $globalGap = $projectProgress - $projectPlanned; @endphp
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Ecart</span>
            <span class="text-2xl font-black {{ $globalGap >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $globalGap >= 0 ? '+' : '' }}{{ $globalGap }}%
            </span>
            <p class="text-xs text-slate-500 mt-1">
                {{ $globalGap >= 0 ? 'En avance ou dans les temps' : 'En retard sur le planning' }}
            </p>
        </div>
    </div>

    {{-- Tableau detaille par activite --}}
    @if(count($activities) > 0)
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                            <th class="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Activite</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Responsable</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Statut</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-48">Planifie vs Reel</th>
                            <th class="px-4 py-3 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Ecart</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @foreach($activities as $act)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-200 max-w-xs truncate">{{ $act['description'] }}</td>
                                <td class="px-4 py-3 text-slate-500 text-xs">{{ $act['responsible'] }}</td>
                                <td class="px-4 py-3">
                                    <x-ui.badge variant="slate" size="sm">{{ $act['status'] ?? 'N/A' }}</x-ui.badge>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="space-y-1.5">
                                        {{-- Barre planifiee --}}
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] text-slate-400 w-10">Plan.</span>
                                            <div class="flex-1 bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                                                <div class="bg-primary/60 h-1.5 rounded-full" style="width: {{ $act['planned'] }}%"></div>
                                            </div>
                                            <span class="text-[10px] font-bold text-slate-400 w-8 text-right">{{ $act['planned'] }}%</span>
                                        </div>
                                        {{-- Barre reelle --}}
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] text-slate-400 w-10">Reel</span>
                                            <div class="flex-1 bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                                                <div class="bg-accent h-1.5 rounded-full" style="width: {{ $act['real'] }}%"></div>
                                            </div>
                                            <span class="text-[10px] font-bold text-slate-500 w-8 text-right">{{ $act['real'] }}%</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs font-bold {{ $act['gap'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
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
            <x-lucide-bar-chart-3 class="w-10 h-10 mx-auto text-slate-300 dark:text-slate-600 mb-3" />
            <p class="text-sm text-slate-400">Aucune activite pour comparer</p>
        </div>
    @endif
</div>
