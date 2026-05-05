@props([
    'variant' => 'primary', // primary, accent, secondary, danger, ghost, outline
    'size' => 'md', // sm, md, lg, xl
    'icon' => null,
    'iconRight' => null,
    'type' => 'button',
    'tag' => 'button',
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center whitespace-nowrap font-bold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed rounded-xl shadow-sm relative overflow-hidden';

    $variants = [
        'primary' => 'bg-primary text-white hover:bg-primary-dark focus:ring-primary shadow-lg shadow-primary/20 border border-transparent',
        'accent' => 'bg-accent text-white hover:bg-accent-dark focus:ring-accent shadow-lg shadow-accent/20 border border-transparent',
        'secondary' => 'bg-secondary text-white hover:bg-slate-700 focus:ring-secondary shadow-lg shadow-slate-200/50 border border-transparent',
        'danger' => 'bg-error text-white hover:bg-error-dark focus:ring-error shadow-lg shadow-error/20 border border-transparent',
        'outline' => 'border border-slate-200 dark:border-slate-700 bg-transparent text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 focus:ring-primary',
        'ghost' => 'bg-transparent text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:ring-slate-400 focus:ring-offset-0 shadow-none border border-transparent',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-[11px] uppercase tracking-wider',
        'md' => 'px-5 py-2.5 text-xs uppercase tracking-widest',
        'lg' => 'px-8 py-3.5 text-xs uppercase tracking-widest',
        'xl' => 'px-12 py-4 text-xs uppercase tracking-widest font-black',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($tag === 'a')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <x-dynamic-component :component="'lucide-' . $icon" class="w-4 h-4 mr-2 shrink-0" />
        @endif

        <span>{{ $slot }}</span>

        @if($iconRight)
            <x-dynamic-component :component="'lucide-' . $iconRight" class="w-4 h-4 ml-2 shrink-0" />
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <x-dynamic-component :component="'lucide-' . $icon" class="w-4 h-4 mr-2 shrink-0" />
        @endif

        <span>{{ $slot }}</span>

        @if($iconRight)
            <x-dynamic-component :component="'lucide-' . $iconRight" class="w-4 h-4 ml-2 shrink-0" />
        @endif
    </button>
@endif
