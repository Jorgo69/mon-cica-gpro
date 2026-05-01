<?php

namespace App\Enums;

enum ActivityStatus: string
{
    case DRAFT = 'Brouillon';
    case ABANDONED = 'Abandonné';
    case STOPPED = 'En Arrêté';
    case PENDING = 'En Attente';
    case ONGOING = 'En Cours';
    case SUSPENDED = 'Suspendu';
    case COMPLETED = 'Terminé';
    case OVERDUE = 'En retard';

    public function label(): string
    {
        return __('enums.activity_status.' . match($this) {
            self::DRAFT => 'draft',
            self::ABANDONED => 'abandoned',
            self::STOPPED => 'stopped',
            self::PENDING => 'pending',
            self::ONGOING => 'ongoing',
            self::SUSPENDED => 'suspended',
            self::COMPLETED => 'completed',
            self::OVERDUE => 'overdue',
        });
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'slate',
            self::ABANDONED => 'error',
            self::STOPPED => 'error',
            self::PENDING => 'warning',
            self::ONGOING => 'primary',
            self::SUSPENDED => 'warning',
            self::COMPLETED => 'success',
            self::OVERDUE => 'error',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::DRAFT => 'file-edit',
            self::ABANDONED => 'x-circle',
            self::STOPPED => 'stop-circle',
            self::PENDING => 'clock',
            self::ONGOING => 'play-circle',
            self::SUSPENDED => 'pause-circle',
            self::COMPLETED => 'check-circle-2',
            self::OVERDUE => 'alert-circle',
        };
    }

    public function hex(): string
    {
        return match($this) {
            self::DRAFT => '#94a3b8',
            self::ABANDONED => '#be123c',
            self::STOPPED => '#be123c',
            self::PENDING => '#F59E0B',
            self::ONGOING => '#0f172a',
            self::SUSPENDED => '#F59E0B',
            self::COMPLETED => '#0d9488',
            self::OVERDUE => '#be123c',
        };
    }
}

