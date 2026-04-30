@props([
    'amount' => 0,
    'currency' => 'XOF',
    'class' => '',
])

@php
    $currencyEnum = $currency instanceof \App\Enums\Currency ? $currency : \App\Enums\Currency::tryFrom($currency);
    $formatted = $currencyEnum ? \App\Services\CurrencyService::format((float) $amount, $currencyEnum) : number_format((float) $amount, 0, ',', ' ');
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>{{ $formatted }}</span>
