@props(['align' => 'left', 'noWrap' => false])

@php
    $alignmentClass = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
    
    $wrapClass = $noWrap ? 'whitespace-nowrap' : '';
@endphp

<td {{ $attributes->merge(['class' => "px-6 py-4 text-sm text-slate-600 dark:text-slate-300 {$alignmentClass} {$wrapClass}"]) }}>
    {{ $slot }}
</td>
