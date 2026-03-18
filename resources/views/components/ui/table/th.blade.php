@props(['align' => 'left'])

@php
    $alignmentClass = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<th {{ $attributes->merge(['class' => "px-6 py-3 {$alignmentClass} text-[10px] font-black text-slate-400 uppercase tracking-widest"]) }}>
    {{ $slot }}
</th>
