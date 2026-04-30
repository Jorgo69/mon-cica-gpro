<?php

namespace App\Enums;

enum LogframeDisplayFormat: string
{
    case TABLE = 'table';
    case TREE = 'tree';
    case CARDS = 'cards';

    public function label(): string
    {
        return match ($this) {
            self::TABLE => 'Matrice',
            self::TREE => 'Arborescence',
            self::CARDS => 'Fiches',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::TABLE => 'table',
            self::TREE => 'git-branch',
            self::CARDS => 'layout-grid',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::TABLE => 'primary',
            self::TREE => 'accent',
            self::CARDS => 'success',
        };
    }
}
