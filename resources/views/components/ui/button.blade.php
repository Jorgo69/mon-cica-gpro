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
        'primary' => 'bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-200 focus:ring-slate-500 shadow-lg shadow-slate-300/50 dark:shadow-slate-900/50 border border-transparent',
        'accent' => 'bg-accent text-white hover:bg-accent-dark focus:ring-accent shadow-lg shadow-accent/20 border border-transparent',
        'secondary' => 'bg-slate-800 dark:bg-slate-200 text-white dark:text-slate-800 hover:bg-slate-700 dark:hover:bg-slate-300 focus:ring-slate-400 shadow-lg shadow-slate-300/50 dark:shadow-slate-900/50 border border-transparent',
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

    $wireClick = $attributes->get('wire:click');
    $targetAttr = $wireClick;
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
        @if($targetAttr)
            {{-- Spinner visible uniquement pendant l'action ciblee --}}
            <svg wire:loading wire:target="{{ $targetAttr }}" class="animate-spin -ml-1 mr-2 w-4 h-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{-- Icone visible uniquement quand PAS en loading --}}
            @if($icon)
                <div wire:loading.remove wire:target="{{ $targetAttr }}" class="contents">
                    <x-dynamic-component :component="'lucide-' . $icon" class="w-4 h-4 mr-2 shrink-0" />
                </div>
            @endif
        @else
            @if($icon)
                <x-dynamic-component :component="'lucide-' . $icon" class="w-4 h-4 mr-2 shrink-0" />
            @endif
        @endif

        <span>{{ $slot }}</span>

        @if($iconRight)
            <x-dynamic-component :component="'lucide-' . $iconRight" class="w-4 h-4 ml-2 shrink-0" />
        @endif
    </button>
@endif
