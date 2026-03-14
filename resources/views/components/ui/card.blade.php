@props([
    'title' => null,
    'footer' => null,
    'noPadding' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden']) }}>
    @if($title || isset($header))
        <div class="px-8 py-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
            @if($title)
                <h3 class="text-xs font-black text-slate-900 dark:text-slate-100 uppercase tracking-widest">{{ $title }}</h3>
            @else
                {{ $header }}
            @endif
        </div>
    @endif

    <div @class([
        'p-8' => !$noPadding,
    ])>
        {{ $slot }}
    </div>

    @if($footer || isset($footerSlot))
        <div class="px-8 py-5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
            {{ $footer ?? $footerSlot }}
        </div>
    @endif
</div>
