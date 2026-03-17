@props([
    'type' => 'text', // text, avatar, image, card, block
    'class' => '',
])

@php
    $baseClasses = 'animate-pulse bg-slate-200 dark:bg-slate-700/50 rounded-md';
    
    $types = [
        'text' => 'h-4 w-3/4',
        'avatar' => 'h-10 w-10 rounded-full',
        'image' => 'h-48 w-full rounded-xl',
        'card' => 'h-32 w-full rounded-xl',
        'block' => 'h-8 w-full',
    ];

    $classes = $baseClasses . ' ' . ($types[$type] ?? $types['text']) . ' ' . $class;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}></div>
