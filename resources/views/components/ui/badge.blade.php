@props([
    'variant' => 'primary', // primary, accent, success, warning, error, info, slate
    'size' => 'md', // sm, md
    'icon' => null,
])

@php
    $baseClasses = 'inline-flex items-center font-bold rounded-lg border uppercase tracking-tighter';
    
    $variants = [
        'primary' => 'bg-primary/5 text-primary border-primary/20',
        'accent' => 'bg-accent/5 text-accent border-accent/20',
        'success' => 'bg-success/5 text-success border-success/20',
        'warning' => 'bg-warning/10 text-warning border-warning/20',
        'error' => 'bg-error/5 text-error border-error/20',
        'info' => 'bg-info/10 text-info border-info/20',
        'slate' => 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-900/50 dark:text-slate-400 dark:border-slate-800',
    ];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-[9px]',
        'md' => 'px-2.5 py-1 text-[10px]',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <x-lucide-{{ $icon }} class="w-3 h-3 mr-1" />
    @endif
    {{ $slot }}
</span>
