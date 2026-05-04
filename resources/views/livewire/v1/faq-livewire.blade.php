<x-ui.page-layout>
    <x-ui.page-header :title="__('faq.title')" :subtitle="__('faq.subtitle')" />

    {{-- Search + Category Filter --}}
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <x-ui.input wire:model.live.debounce.300ms="search" :placeholder="__('faq.search_placeholder')" icon="search" class="flex-1" />
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
            <button wire:click="$set('activeCategory', 'all')"
                    class="px-3 py-1.5 text-xs font-bold rounded-lg whitespace-nowrap transition-all {{ $activeCategory === 'all' ? 'bg-accent text-white shadow-lg' : 'bg-surface text-muted hover:text-heading' }}">
                {{ __('common.all') }}
            </button>
            @foreach($categories as $key => $label)
                <button wire:click="$set('activeCategory', '{{ $key }}')"
                        class="px-3 py-1.5 text-xs font-bold rounded-lg whitespace-nowrap transition-all {{ $activeCategory === $key ? 'bg-accent text-white shadow-lg' : 'bg-surface text-muted hover:text-heading' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- FAQ Items --}}
    @if($items->isEmpty())
        <x-ui.empty-state icon="help-circle" :title="__('faq.no_results')" />
    @else
        <div class="space-y-3">
            @foreach($items as $i => $item)
                <div x-data="{ open: false }" class="bg-card rounded-xl border border-border-light overflow-hidden">
                    <button @click="open = !open" class="w-full px-5 py-4 flex items-center justify-between text-left group">
                        <div class="flex items-center gap-3">
                            <span class="text-[9px] font-black text-accent uppercase tracking-widest bg-accent/10 px-2 py-0.5 rounded-md">{{ $categories[$item['category']] ?? $item['category'] }}</span>
                            <span class="text-sm font-bold text-heading group-hover:text-accent transition-colors">{{ $item['q'] }}</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 text-muted flex-shrink-0 transition-transform" ::class="open ? 'rotate-180' : ''" />
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-5 pb-4 text-sm text-body leading-relaxed border-t border-border-light pt-3 ml-[72px]">
                            {!! $item['a'] !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</x-ui.page-layout>
