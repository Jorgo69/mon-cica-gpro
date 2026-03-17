<div>
    <div 
        x-data="{ 
            show: @entangle('isVisible'),
            init() {
            }
        }"
        @keydown.window.ctrl.k.prevent="show = true; $nextTick(() => $refs.searchInput.focus())"
        @keydown.window.meta.k.prevent="show = true; $nextTick(() => $refs.searchInput.focus())"
        @keydown.window.escape="show = false"
        @toggle-search.window="show = true; $nextTick(() => $refs.searchInput.focus())"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-[60] flex items-start justify-center pt-20 px-4 sm:px-6 pointer-events-none"
    >
    {{-- Backdrop --}}
    <div 
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm pointer-events-auto"
        @click="show = false"
    ></div>

    {{-- Search Modal --}}
    <div 
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden pointer-events-auto"
    >
        {{-- Search Input --}}
        <div class="relative p-6 border-b border-slate-100 dark:border-slate-800">
            <x-lucide-search class="absolute left-10 top-1/2 -translate-y-1/2 w-6 h-6 text-slate-400" />
            <input 
                x-ref="searchInput"
                wire:model.live.debounce.300ms="search"
                type="text" 
                placeholder="Rechercher un projet, une activité... (Ctrl+K)" 
                class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-800/50 border-none rounded-2xl text-lg font-medium text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-accent placeholder-slate-400"
            />
        </div>

        {{-- Results --}}
        <div class="max-h-[60vh] overflow-y-auto p-4">
            @if(empty($search))
                <div class="p-8 text-center">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Commencez à taper pour rechercher...</p>
                </div>
            @elseif(empty($results))
                <div class="p-8 text-center">
                    <x-lucide-search-x class="w-12 h-12 text-slate-200 dark:text-slate-700 mx-auto mb-4" />
                    <p class="text-sm font-bold text-slate-400">Aucun résultat trouvé pour "{{ $search }}"</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($results as $result)
                        <a 
                            href="{{ $result['url'] }}" 
                            wire:navigate
                            @click="show = false"
                            class="flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group"
                        >
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0 group-hover:bg-white dark:group-hover:bg-slate-700 transition-colors">
                                @switch($result['icon'])
                                    @case('folder-kanban') <x-lucide-folder-kanban class="w-6 h-6 text-primary" /> @break
                                    @case('activity') <x-lucide-activity class="w-6 h-6 text-accent" /> @break
                                    @case('users') <x-lucide-users class="w-6 h-6 text-warning" /> @break
                                    @default <x-lucide-info class="w-6 h-6 text-slate-400" />
                                @endswitch
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="text-base font-bold text-slate-800 dark:text-slate-100 truncate">{{ $result['title'] }}</h4>
                                    <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $result['type'] }}</span>
                                </div>
                                <p class="text-sm text-slate-500 truncate">{{ $result['subtitle'] }}</p>
                            </div>
                            <x-lucide-chevron-right class="w-5 h-5 text-slate-300 group-hover:text-accent transition-colors" />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="p-4 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 shadow-sm">ESC</kbd> Fermer
                </span>
            </div>
            <div class="text-[10px] font-black text-slate-300 uppercase tracking-widest leading-none">CICA-GPRO SYSTEM</div>
        </div>
    </div>
</div>
