<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case DRAFT = 'Brouillon';
    case PENDING = 'En attente';
    case ACTIVE = 'En cours';
    case ON_HOLD = 'En pause';
    case COMPLETED = 'Terminé';
    case CANCELLED = 'Annulé';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Brouillon',
            self::PENDING => 'En attente',
            self::ACTIVE => 'En cours',
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
