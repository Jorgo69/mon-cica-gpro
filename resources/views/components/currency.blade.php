@props(['amount' => 0, 'currency' => null])
@php
    $curr = $currency instanceof \App\Enums\Currency ? $currency : \App\Enums\Currency::tryFrom($currency ?? 'XOF');
    $symbol = $curr?->symbol() ?? '';
    $formatted = number_format((float) $amount, 0, ',', ' ');
@endphp{{ $formatted }} {{ $symbol }}