<div class="space-y-4">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
            <x-lucide-history class="w-5 h-5 text-accent" />
            Historique des activités
        </h3>
        <div class="text-xs text-slate-400">
            Total: {{ $activities->total() }} évènements
        </div>
    </div>

    <div class="relative">
        {{-- Vertical Line --}}
        <div class="absolute left-4 top-0 bottom-0 w-px bg-slate-200 dark:bg-slate-800 ml-[11px]"></div>

        <div class="space-y-8">
            @forelse ($activities as $activity)
                <div class="relative pl-12">
                    {{-- Icon Badge --}}
                    <div class="absolute left-0 top-0 w-8 h-8 rounded-full border-4 border-white dark:border-slate-900 flex items-center justify-center z-10 
                        @switch($activity->event)
                            @case('created') bg-success/10 text-success @break
                            @case('updated') bg-accent/10 text-accent @break
                            @case('deleted') bg-error/10 text-error @break
                            @default bg-slate-100 text-slate-500 @break
                        @endswitch">
                        @switch($activity->event)
                            @case('created') <x-lucide-plus class="w-4 h-4" /> @break
                            @case('updated') <x-lucide-edit class="w-4 h-4" /> @break
                            @case('deleted') <x-lucide-trash-2 class="w-4 h-4" /> @break
                            @default <x-lucide-info class="w-4 h-4" /> @break
                        @endswitch
                    </div>

                    <div class="bg-white dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-slate-800 dark:text-slate-100">
                                    {{ $activity->causer->name ?? 'Système' }}
                                </span>
                                <span class="text-xs text-slate-500">
                                    {{ $activity->description }}
                                </span>
                            </div>
                            <span class="text-[10px] font-mono text-slate-400 bg-slate-50 dark:bg-slate-800 px-2 py-0.5 rounded">
                                {{ $activity->created_at->diffForHumans() }}
                            </span>
                        </div>

                        @if($activity->event === 'updated' && isset($activity->properties['old']))
                            <div class="space-y-2">
                                @foreach($activity->properties['attributes'] as $key => $newValue)
                                    @php 
                                        $oldValue = $activity->properties['old'][$key] ?? 'N/A';
                                        // Skip common hidden fields
                                        if(in_array($key, ['updated_at', 'created_at', 'id', 'organization_id'])) continue;
                                    @endphp
                                    <div class="text-xs flex flex-col sm:flex-row sm:items-center gap-1 bg-slate-50 dark:bg-slate-800/30 p-2 rounded-lg">
                                        <span class="font-bold text-slate-500 min-w-[100px]">{{ str_replace('_', ' ', ucfirst($key)) }} :</span>
                                        <div class="flex items-center gap-2 overflow-hidden">
                                            <span class="text-error/70 line-through truncate max-w-[150px]">{{ is_array($oldValue) ? json_encode($oldValue) : $oldValue }}</span>
                                            <x-lucide-arrow-right class="w-3 h-3 text-slate-300 shrink-0" />
                                            <span class="text-success font-medium truncate">{{ is_array($newValue) ? json_encode($newValue) : $newValue }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($activity->event === 'created')
                             <div class="text-xs text-slate-500 italic">
                                Données initiales enregistrées.
                             </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-slate-400">
                    <x-lucide-ghost class="w-8 h-8 mx-auto mb-2 opacity-20" />
                    <p class="text-sm">Aucune activité enregistrée pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-6">
        {{ $activities->links() }}
    </div>
</div>
