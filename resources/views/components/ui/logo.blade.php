@props([
    'size' => 'md',       // sm (32px), md (40px), lg (48px), xl (56px)
    'withText' => true,
    'dark' => false,      // force dark variant (white text, for dark backgrounds)
])

@php
    $sizes = [
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-12 h-12',
        'xl' => 'w-14 h-14',
    ];
    $textSizes = [
        'sm' => 'text-base',
        'md' => 'text-xl',
        'lg' => 'text-xl',
        'xl' => 'text-2xl',
    ];
    $imgSize = $sizes[$size] ?? $sizes['md'];
    $textSize = $textSizes[$size] ?? $textSizes['md'];
    $logo = config('gpro.logo');
    $logoDark = config('gpro.logo_dark');
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2.5']) }}>
    @if($logo)
        @if($logoDark)
            <img src="{{ asset($logo) }}" alt="{{ config('app.name') }}" class="{{ $imgSize }} object-contain rounded-xl {{ $dark ? 'hidden' : 'block dark:hidden' }}" />
            <img src="{{ asset($logoDark) }}" alt="{{ config('app.name') }}" class="{{ $imgSize }} object-contain rounded-xl {{ $dark ? 'block' : 'hidden dark:block' }}" />
        @else
            <img src="{{ asset($logo) }}" alt="{{ config('app.name') }}" class="{{ $imgSize }} object-contain rounded-xl" />
        @endif
    @else
        <div class="{{ $imgSize }} {{ $dark ? 'bg-white/20' : 'bg-accent' }} rounded-xl flex items-center justify-center">
            <span class="text-white font-black {{ $size === 'sm' ? 'text-sm' : ($size === 'xl' ? 'text-2xl' : 'text-lg') }}">G</span>
        </div>
    @endif

    @if($withText)
        <span class="font-black tracking-tighter {{ $textSize }} {{ $dark ? 'text-white' : 'text-heading' }}">{{ config('app.name') }}</span>
    @endif
</div>
