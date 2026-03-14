<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case DRAFT = 'brouillon';
    case PENDING = 'en_attente';
    case ACTIVE = 'actif';
    case ON_HOLD = 'en_pause';
    case COMPLETED = 'termine';
    case CANCELLED = 'annule';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Brouillon',
            self::PENDING => 'En attente',
            self::ACTIVE => 'Actif',
            self::ON_HOLD => 'En pause',
            self::COMPLETED => 'Terminé',
            self::CANCELLED => 'Annulé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'slate',
            self::PENDING => 'warning',
            self::ACTIVE => 'primary',
            self::ON_HOLD => 'info',
            self::COMPLETED => 'success',
            self::CANCELLED => 'error',
        };
    }
}
