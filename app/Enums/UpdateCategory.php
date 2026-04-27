<?php

namespace App\Enums;

enum UpdateCategory: string
{
    case ACADEMIC = 'Academic';
    case ADMINISTRATIVE = 'Administrative';
    case EMERGENCY = 'Emergency';
    case EVENT = 'Event';
    case OTHER = 'Other';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
