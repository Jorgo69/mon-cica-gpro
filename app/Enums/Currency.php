<?php

namespace App\Enums;

enum Currency: string
{
    case XOF = 'XOF';
    case EUR = 'EUR';
    case USD = 'USD';
    case GBP = 'GBP';
    case XAF = 'XAF';
    case NGN = 'NGN';
    case CHF = 'CHF';
    case CAD = 'CAD';

    public function label(): string
    {
        return match ($this) {
            self::XOF => 'Franc CFA (BCEAO)',
            self::EUR => 'Euro',
            self::USD => 'Dollar US',
            self::GBP => 'Livre Sterling',
            self::XAF => 'Franc CFA (BEAC)',
            self::NGN => 'Naira',
            self::CHF => 'Franc Suisse',
            self::CAD => 'Dollar Canadien',
        };
    }

    public function symbol(): string
    {
        return match ($this) {
            self::XOF, self::XAF => 'FCFA',
            self::EUR => '€',
            self::USD => '$',
            self::GBP => '£',
            self::NGN => '₦',
            self::CHF => 'CHF',
            self::CAD => 'CA$',
        };
    }

    public function decimals(): int
    {
        return match ($this) {
            self::XOF, self::XAF => 0,
            default => 2,
        };
    }
}
