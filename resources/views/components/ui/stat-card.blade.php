@props([
    'value' => 0,
    'label' => '',
    'icon' => null,
    'variant' => 'default', // default, success, accent, warning, error
])

@php
    $variants = [
        'default' => 'bg-slate-50 dark:bg-slate-800/50 text-slate-700 dark:text-slate-300',
        'success' => 'bg-success/5 dark:bg-success/10 text-success',
        'accent'  => 'bg-accent/5 dark:bg-accent/10 text-accent',
        'warning' => 'bg-warning/5 dark:bg-warning/10 text-warning',
        'error'   => 'bg-error/5 dark:bg-error/10 text-error',
        'info'    => 'bg-info/5 dark:bg-info/10 text-info',
    ];
    $colorClass = $variants[$variant] ?? $variants['default'];
@endphp

<div {{ $attributes->merge(['class' => "rounded-xl p-4 transition-all duration-200 $colorClass"]) }}>
    <div class="flex items-center justify-between mb-1">
        <span class="text-2xl font-black leading-none">{{ $value }}</span>
        @if($icon)
            <x-dynamic-component :component="'lucide-' . $icon" class="w-4 h-4 opacity-40" />
        @endif
    </div>
    <p class="text-[11px] font-semibold uppercase tracking-wider opacity-70">{{ $label }}</p>
</div>
