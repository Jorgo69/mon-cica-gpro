<?php

namespace App\Services;

use App\Enums\Currency;
use App\Models\ExchangeRate;
use Illuminate\Support\Carbon;

class CurrencyService
{
    public static function format(float $amount, Currency|string $currency): string
    {
        $currency = $currency instanceof Currency ? $currency : Currency::from($currency);

        $formatted = number_format($amount, $currency->decimals(), ',', ' ');

        return match ($currency) {
            Currency::EUR => "{$formatted} €",
            Currency::USD => "\${$formatted}",
            Currency::GBP => "£{$formatted}",
            Currency::NGN => "₦{$formatted}",
            Currency::CAD => "CA\${$formatted}",
            default => "{$formatted} {$currency->symbol()}",
        };
    }

    public static function convert(float $amount, Currency $from, Currency $to, ?Carbon $date = null): float
    {
        return ExchangeRate::convert($amount, $from, $to, $date);
    }
}
