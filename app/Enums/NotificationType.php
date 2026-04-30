<?php

namespace App\Enums;

enum NotificationType: string
{
    case PROJECT_SUBMITTED = 'project_submitted';
    case PROJECT_STATUS_UPDATED = 'project_status_updated';
    case ACTIVITY_ASSIGNED = 'activity_assigned';
    case ACTIVITY_PROGRESS_UPDATED = 'activity_progress_updated';
    case DEADLINE_APPROACHING = 'deadline_approaching';
    case ACTIVITY_OVERDUE = 'activity_overdue';
    case BUDGET_THRESHOLD = 'budget_threshold';
    case WEEKLY_DIGEST = 'weekly_digest';
    case INVITATION = 'invitation';

    public function label(): string
    {
        return match ($this) {
            self::PROJECT_SUBMITTED => 'Projet soumis',
            self::PROJECT_STATUS_UPDATED => 'Statut projet modifie',
            self::ACTIVITY_ASSIGNED => 'Activite assignee',
            self::ACTIVITY_PROGRESS_UPDATED => 'Progression activite',
            self::DEADLINE_APPROACHING => 'Echeance proche',
            self::ACTIVITY_OVERDUE => 'Activite en retard',
            self::BUDGET_THRESHOLD => 'Seuil budget',
            self::WEEKLY_DIGEST => 'Resume hebdomadaire',
            self::INVITATION => 'Invitation',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PROJECT_SUBMITTED => 'send',
            self::PROJECT_STATUS_UPDATED => 'refresh-cw',
            self::ACTIVITY_ASSIGNED => 'user-plus',
            self::ACTIVITY_PROGRESS_UPDATED => 'trending-up',
            self::DEADLINE_APPROACHING => 'clock',
            self::ACTIVITY_OVERDUE => 'alert-triangle',
            self::BUDGET_THRESHOLD => 'wallet',
            self::WEEKLY_DIGEST => 'newspaper',
            self::INVITATION => 'mail',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PROJECT_SUBMITTED => 'accent',
            self::PROJECT_STATUS_UPDATED => 'info',
            self::ACTIVITY_ASSIGNED => 'success',
            self::ACTIVITY_PROGRESS_UPDATED => 'accent',
            self::DEADLINE_APPROACHING => 'warning',
            self::ACTIVITY_OVERDUE => 'error',
            self::BUDGET_THRESHOLD => 'warning',
            self::WEEKLY_DIGEST => 'slate',
            self::INVITATION => 'accent',
        };
    }

    public function defaultChannels(): array
    {
        return match ($this) {
            self::WEEKLY_DIGEST => ['mail'],
            self::INVITATION => ['mail'],
            self::ACTIVITY_OVERDUE => ['database', 'mail'],
            self::DEADLINE_APPROACHING => ['database', 'mail'],
            default => ['database', 'mail'],
        };
    }

    /**
     * Types configurables par l'utilisateur (exclut invitation et digest)
     */
    public static function userConfigurable(): array
    {
        return array_filter(self::cases(), fn($t) => !in_array($t, [self::INVITATION, self::WEEKLY_DIGEST]));
    }
}
