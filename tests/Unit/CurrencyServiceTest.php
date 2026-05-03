<?php

use App\Enums\Currency;
use App\Services\CurrencyService;

/*
|--------------------------------------------------------------------------
| CurrencyService::format()
|--------------------------------------------------------------------------
*/

test('format XOF: pas de decimales, symbole FCFA en suffixe', function () {
    $result = CurrencyService::format(1500000, Currency::XOF);

    expect($result)->toBe('1 500 000 FCFA');
});

test('format EUR: 2 decimales, symbole euro en suffixe', function () {
    $result = CurrencyService::format(1234.56, Currency::EUR);

    expect($result)->toBe('1 234,56 €');
});

test('format USD: 2 decimales, symbole dollar en prefixe', function () {
    $result = CurrencyService::format(999.99, Currency::USD);

    expect($result)->toBe('$999,99');
});

test('format GBP: 2 decimales, symbole livre en prefixe', function () {
    $result = CurrencyService::format(500.00, Currency::GBP);

    expect($result)->toBe('£500,00');
});

test('format NGN: 2 decimales, symbole naira en prefixe', function () {
    $result = CurrencyService::format(75000.50, Currency::NGN);

    expect($result)->toBe('₦75 000,50');
});

test('format CAD: 2 decimales, symbole CA$ en prefixe', function () {
    $result = CurrencyService::format(1200.00, Currency::CAD);

    expect($result)->toBe('CA$1 200,00');
});

test('format CHF: 2 decimales, symbole CHF en suffixe (default branch)', function () {
    $result = CurrencyService::format(850.75, Currency::CHF);

    expect($result)->toBe('850,75 CHF');
});

test('format XAF: pas de decimales, symbole FCFA en suffixe', function () {
    $result = CurrencyService::format(250000, Currency::XAF);

    expect($result)->toBe('250 000 FCFA');
});

test('format avec montant zero', function () {
    expect(CurrencyService::format(0, Currency::EUR))->toBe('0,00 €');
    expect(CurrencyService::format(0, Currency::XOF))->toBe('0 FCFA');
});

test('format avec montant negatif', function () {
    $result = CurrencyService::format(-500.50, Currency::EUR);

    expect($result)->toBe('-500,50 €');
});

test('format accepte une string de devise valide', function () {
    $result = CurrencyService::format(100, 'EUR');

    expect($result)->toBe('100,00 €');
});

test('format avec grands montants applique le separateur de milliers', function () {
    $result = CurrencyService::format(1234567890.12, Currency::EUR);

    expect($result)->toBe('1 234 567 890,12 €');
});
