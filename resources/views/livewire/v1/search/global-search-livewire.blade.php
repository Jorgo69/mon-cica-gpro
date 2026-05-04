<div>
    <div
        x-data="{
            show: @entangle('isVisible'),
            selectedIndex: -1,
            get allItems() {
                return this.$refs.resultsList ? [...this.$refs.resultsList.querySelectorAll('[data-search-item]')] : [];
            },
            navigate(direction) {
                const items = this.allItems;
                if (!items.length) return;
                this.selectedIndex = Math.max(-1, Math.min(items.length - 1, this.selectedIndex + direction));
                if (this.selectedIndex >= 0) items[this.selectedIndex].focus();
            },
            enter() {
                const items = this.allItems;
                if (this.selectedIndex >= 0 && items[this.selectedIndex]) {
                    items[this.selectedIndex].click();
                }
            }
        }"
        @keydown.window.ctrl.k.prevent="show = true; $nextTick(() => { $refs.searchInput?.focus(); selectedIndex = -1; })"
        @keydown.window.meta.k.prevent="show = true; $nextTick(() => { $refs.searchInput?.focus(); selectedIndex = -1; })"
        @keydown.window.escape="show = false"
        @toggle-search.window="show = true; $nextTick(() => $refs.searchInput?.focus())"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-[60] flex items-start justify-center pt-16 px-4 sm:px-6 pointer-events-none"
    >
        {{-- Backdrop --}}
        <div
            x-show="show"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm pointer-events-auto"
            @click="show = false"
        ></div>

        {{-- Search Modal --}}
        <div
            x-show="show"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @keydown.arrow-down.prevent="navigate(1)"
            @keydown.arrow-up.prevent="navigate(-1)"
            @keydown.enter.prevent="enter()"
            class="relative w-full max-w-2xl bg-card rounded-2xl shadow-2xl border border-border-light overflow-hidden pointer-events-auto"
        >
            {{-- Input --}}
            <div class="flex items-center gap-3 px-5 py-4 border-b border-border-light">
                <x-lucide-search class="w-5 h-5 text-muted flex-shrink-0" />
                <input
                    x-ref="searchInput"
                    wire:model.live.debounce.250ms="search"
                    type="text"
                    placeholder="{{ __('search.placeholder') }}"
                    class="flex-1 bg-transparent border-none text-sm font-medium text-heading focus:ring-0 focus:outline-none placeholder-muted"
                />
                <kbd class="hidden sm:inline-flex items-center px-1.5 py-0.5 rounded bg-surface border border-border-light text-[9px] font-black text-muted">ESC</kbd>
            </div>

            {{-- Results --}}
            <div x-ref="resultsList" class="max-h-[60vh] overflow-y-auto">

                @if(empty($search))
                    {{-- Etat initial : actions rapides + historique --}}
                    <div class="p-3">

                        {{-- Recherches recentes --}}
                        @if(!empty($recentSearches))
                        <div class="mb-4">
                            <div class="flex items-center justify-between px-2 mb-2">
                                <span class="text-[9px] font-black text-muted uppercase tracking-widest">{{ __('search.recent_searches') }}</span>
                                <button wire:click="clearRecentSearches" class="text-[9px] font-bold text-subtle hover:text-accent transition-colors">{{ __('search.clear') }}</button>
                            </div>
                            @foreach($recentSearches as $index => $recent)
                                <button wire:click="setSearchFromRecent({{ $index }})"
                                    data-search-item
                                    class="flex items-center gap-3 w-full px-3 py-2 rounded-xl text-left hover:bg-surface focus:bg-surface focus:outline-none transition-colors">
                                    <x-lucide-clock class="w-4 h-4 text-muted" />
                                    <span class="text-xs text-body">{{ $recent }}</span>
                                </button>
                            @endforeach
                        </div>
                        @endif

                        {{-- Actions rapides --}}
                        @if(!empty($quickActions))
                        <div>
                            <span class="text-[9px] font-black text-muted uppercase tracking-widest px-2 block mb-2">{{ __('search.quick_actions') }}</span>
                            @foreach($quickActions as $action)
                                <a href="{{ $action['url'] }}"
                                    data-search-item
                                    @click="show = false"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-surface focus:bg-surface focus:outline-none transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center">
                                        <x-dynamic-component :component="'lucide-' . $action['icon']" class="w-4 h-4 text-accent" />
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-heading">{{ $action['title'] }}</p>
                                        <p class="text-[10px] text-subtle">{{ $action['subtitle'] }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        @endif

                        @if(empty($recentSearches) && empty($quickActions))
                        <div class="p-8 text-center">
                            <x-lucide-search class="w-8 h-8 text-muted mx-auto mb-3 opacity-50" />
                            <p class="text-xs font-bold text-muted">{{ __('search.start_typing') }}</p>
                        </div>
                        @endif
                    </div>

                @elseif(empty($results))
                    {{-- Aucun resultat --}}
                    <div class="p-8 text-center">
                        <x-lucide-search-x class="w-10 h-10 text-muted mx-auto mb-3 opacity-50" />
                        <p class="text-sm font-bold text-heading">{{ __('search.no_results_title') }}</p>
                        <p class="text-xs text-subtle mt-1">{{ __('search.no_match', ['query' => $search]) }}</p>
                    </div>

                @else
                    {{-- Resultats groupes par categorie --}}
                    <div class="p-3">
                        @php
                            $grouped = collect($results)->groupBy('category');
                        @endphp

                        @foreach($grouped as $category => $items)
                            <div class="mb-3 last:mb-0">
                                <span class="text-[9px] font-black text-muted uppercase tracking-widest px-2 block mb-1">{{ $category }}</span>
                                @foreach($items as $result)
                                    <button
                                        wire:click="selectResult('{{ $result['url'] }}')"
                                        data-search-item
                                        class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-left hover:bg-surface focus:bg-surface focus:outline-none transition-colors group">
                                        <div class="w-8 h-8 rounded-lg bg-surface-alt flex items-center justify-center flex-shrink-0 group-hover:bg-accent/10 transition-colors">
                                            <x-dynamic-component :component="'lucide-' . $result['icon']" class="w-4 h-4 text-subtle group-hover:text-accent transition-colors" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-heading truncate">{{ $result['title'] }}</p>
                                            <p class="text-[10px] text-subtle truncate">{{ $result['subtitle'] }}</p>
                                        </div>
                                        <x-lucide-arrow-right class="w-3.5 h-3.5 text-muted opacity-0 group-hover:opacity-100 transition-opacity" />
                                    </button>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="px-4 py-2.5 bg-surface/50 dark:bg-surface-alt/30 border-t border-border-light flex items-center justify-between">
                <div class="flex items-center gap-4 text-[9px] font-bold text-muted">
                    <span class="inline-flex items-center gap-1">
                        <kbd class="px-1 py-0.5 rounded bg-card border border-border-light shadow-sm text-[8px]">&uarr;&darr;</kbd> {{ __('search.navigate') }}
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <kbd class="px-1 py-0.5 rounded bg-card border border-border-light shadow-sm text-[8px]">&crarr;</kbd> {{ __('search.open') }}
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <kbd class="px-1 py-0.5 rounded bg-card border border-border-light shadow-sm text-[8px]">esc</kbd> {{ __('common.close') }}
                    </span>
                </div>
                <span class="text-[9px] font-black text-muted uppercase tracking-widest">CICA-GPRO</span>
            </div>
        </div>
    </div>
</div>
