<?php

namespace App\Enums;

enum FieldType: string
{
    case TEXT = 'text';
    case SELECT = 'select';
    case DATE = 'date';
    case TEXTAREA = 'textarea';
    case NUMBER = 'number';

    public function label(): string
    {
        return match($this) {
            self::TEXT => 'Texte court',
            self::SELECT => 'Liste de sélection',
            self::DATE => 'Date',
            self::TEXTAREA => 'Zone de texte long',
            self::NUMBER => 'Nombre',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::TEXT => 'type',
            self::SELECT => 'list',
            self::DATE => 'calendar',
            self::TEXTAREA => 'align-left',
            self::NUMBER => 'hash',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::TEXT => 'text-blue-500',
            self::SELECT => 'text-purple-500',
            self::DATE => 'text-amber-500',
            self::TEXTAREA => 'text-emerald-500',
            self::NUMBER => 'text-rose-500',
        };
    }
}
