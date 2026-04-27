<?php

namespace App\Enums;

enum Year: int
{
    case Y2023 = 2023;
    case Y2024 = 2024;
    case Y2025 = 2025;
    case Y2026 = 2026;
    case Y2027 = 2027;
    case Y2028 = 2028;
    case Y2029 = 2029;
    case Y2030 = 2030;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
