@props(['class' => '', 'maxWidth' => '7xl'])

@php
    $maxWidthClass = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '4xl' => 'sm:max-w-4xl',
        '5xl' => 'sm:max-w-5xl',
        '6xl' => 'sm:max-w-6xl',
        '7xl' => 'sm:max-w-7xl',
        'full' => 'sm:max-w-full',
    ][$maxWidth];
@endphp

<main {{ $attributes->merge(['class' => 'flex-1 overflow-x-hidden overflow-y-auto focus:outline-none']) }}>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="{{ $maxWidthClass }} mx-auto space-y-6 {{ $class }}">
            {{ $slot }}
        </div>
    </div>
</main>