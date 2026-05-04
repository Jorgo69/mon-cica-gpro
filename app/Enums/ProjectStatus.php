<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case DRAFT = 'Brouillon';
    case SUBMITTED = 'Soumis';
    case UNDER_REVIEW = 'En révision';
    case APPROVED = 'Approuvé';
    case REJECTED = 'Rejeté';
    case PENDING = 'En attente';
    case ACTIVE = 'En cours';
    case ON_HOLD = 'En pause';
    case COMPLETED = 'Terminé';
    case CANCELLED = 'Annulé';

    public function label(): string
    {
        return __('enums.project_status.' . $this->key());
    }

    public function key(): string
    {
        return match($this) {
            self::DRAFT => 'draft',
            self::SUBMITTED => 'submitted',
            self::UNDER_REVIEW => 'under_review',
            self::APPROVED => 'approved',
            self::REJECTED => 'rejected',
            self::PENDING => 'pending',
            self::ACTIVE => 'active',
            self::ON_HOLD => 'on_hold',
            self::COMPLETED => 'completed',
            self::CANCELLED => 'cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'slate',
            self::SUBMITTED => 'amber',
            self::UNDER_REVIEW => 'blue',
            self::APPROVED => 'emerald',
            self::REJECTED => 'error',
            self::PENDING => 'warning',
            self::ACTIVE => 'primary',
            self::ON_HOLD => 'info',
            self::COMPLETED => 'success',
            self::CANCELLED => 'error',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::DRAFT => 'file-edit',
            self::SUBMITTED => 'send',
            self::UNDER_REVIEW => 'eye',
            self::APPROVED => 'check-circle',
            self::REJECTED => 'x-circle',
            self::PENDING => 'clock',
            self::ACTIVE => 'play',
            self::ON_HOLD => 'pause',
            self::COMPLETED => 'check-check',
            self::CANCELLED => 'ban',
        };
    }

    public function isOperational(): bool
    {
        return in_array($this, [self::ACTIVE, self::ON_HOLD]);
    }

    /**
     * Allowed transitions from this status.
     */
    public function allowedTransitions(): array
    {
        return match($this) {
            self::DRAFT => [self::SUBMITTED],
            self::SUBMITTED => [self::UNDER_REVIEW, self::REJECTED, self::DRAFT],
            self::UNDER_REVIEW => [self::APPROVED, self::REJECTED],
            self::APPROVED => [self::ACTIVE],
            self::REJECTED => [self::DRAFT],
            self::ACTIVE => [self::ON_HOLD, self::COMPLETED, self::CANCELLED],
            self::ON_HOLD => [self::ACTIVE, self::CANCELLED],
            self::PENDING => [self::ACTIVE, self::CANCELLED],
            self::COMPLETED => [],
            self::CANCELLED => [self::DRAFT],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions());
    }

    /**
     * Is this a workflow status (needs approval)?
     */
    public function isInWorkflow(): bool
    {
        return in_array($this, [self::SUBMITTED, self::UNDER_REVIEW, self::APPROVED, self::REJECTED]);
    }

    public function hex(): string
    {
        return match($this) {
            self::DRAFT => '#94a3b8',
            self::SUBMITTED => '#F59E0B',
            self::UNDER_REVIEW => '#3B82F6',
            self::APPROVED => '#10B981',
            self::REJECTED => '#EF4444',
            self::PENDING => '#F59E0B',
            self::ACTIVE => '#0f172a',
            self::ON_HOLD => '#3B82F6',
            self::COMPLETED => '#0d9488',
            self::CANCELLED => '#be123c',
        };
    }
}
