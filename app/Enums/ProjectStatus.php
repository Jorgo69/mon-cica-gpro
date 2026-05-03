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
        return __('enums.project_status.' . match($this) {
            self::DRAFT => 'draft',
            self::PENDING => 'pending',
            self::ACTIVE => 'active',
            self::ON_HOLD => 'on_hold',
            self::COMPLETED => 'completed',
            self::CANCELLED => 'cancelled',
        });
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

    /**
     * Can activities be executed (progress, expenses, status changes)?
     */
    public function isOperational(): bool
    {
        return in_array($this, [self::ACTIVE, self::ON_HOLD]);
    }

    public function hex(): string
    {
        return match($this) {
            self::DRAFT => '#94a3b8',   // Slate 400
            self::PENDING => '#F59E0B', // Amber 500
            self::ACTIVE => '#0f172a',  // Slate 900 (Primary)
            self::ON_HOLD => '#3B82F6', // Blue 500
            self::COMPLETED => '#0d9488', // Teal 600 (Success)
            self::CANCELLED => '#be123c', // Rose 700 (Error)
        };
    }
}