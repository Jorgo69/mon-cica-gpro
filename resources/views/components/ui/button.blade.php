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
    $baseClasses = 'inline-flex items-center justify-center font-bold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed rounded-xl shadow-sm tracking-tight';
    
    $variants = [
        'primary' => 'bg-primary text-white hover:bg-primary-dark focus:ring-primary shadow-lg shadow-primary/20',
        'accent' => 'bg-accent text-white hover:bg-accent-dark focus:ring-accent shadow-lg shadow-accent/20',
        'secondary' => 'bg-secondary text-white hover:bg-slate-700 focus:ring-secondary shadow-lg shadow-slate-200/50',
        'danger' => 'bg-error text-white hover:bg-error-dark focus:ring-error shadow-lg shadow-error/20',
        'outline' => 'border border-slate-200 dark:border-slate-700 bg-transparent text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 focus:ring-primary',
        'ghost' => 'bg-transparent text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:ring-slate-400 focus:ring-offset-0 shadow-none',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-[11px] uppercase tracking-wider',
        'md' => 'px-5 py-2.5 text-xs uppercase tracking-widest',
        'lg' => 'px-8 py-3.5 text-xs uppercase tracking-[0.15em]',
        'xl' => 'px-12 py-4 text-xs uppercase tracking-[0.2em] font-black',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<{{ $tag }} {{ $tag === 'a' ? 'href='.$href : '' }} {{ $attributes->merge(['type' => $tag === 'button' ? $type : null, 'class' => $classes]) }}>
    @if($icon)
        <x-lucide-{{ $icon }} class="w-4 h-4 mr-2" />
    @endif

    {{ $slot }}

    @if($iconRight)
        <x-lucide-{{ $iconRight }} class="w-4 h-4 ml-2" />
    @endif
</{{ $tag }}>
