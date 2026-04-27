<?php

namespace App\Enums;

enum UpdateRecipient: string
{
    case STUDENTS = 'students';
    case PARENTS = 'parents';
    case BOTH = 'both';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
