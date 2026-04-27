<?php

namespace App\Enums;

enum UpdateTargetType: string
{
    case ALL = 'all';
    case BATCH = 'batch';
    case STANDARD = 'standard';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
