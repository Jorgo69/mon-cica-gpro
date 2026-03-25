<?php

namespace App\Enums;

/**
 * Type d'entrée dans project_updates.
 * Distingue les mises à jour opérationnelles des évaluations formelles.
 */
enum UpdateType: string
{
    case PROGRESS   = 'progress';   // Mise à jour de progression (opérationnelle, régulière)
    case EVALUATION = 'evaluation'; // Évaluation formelle (ponctuelle, superviseur/bailleur)

    public function label(): string
    {
        return match($this) {
            self::PROGRESS   => 'Mise à jour',
            self::EVALUATION => 'Évaluation',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PROGRESS   => 'primary',
            self::EVALUATION => 'indigo',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::PROGRESS   => 'trending-up',
            self::EVALUATION => 'clipboard-check',
        };
    }
}
