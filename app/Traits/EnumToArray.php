<?php

namespace App\Traits;

trait EnumToArray
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function key(string $currency): bool
    {
        return in_array($currency, self::array(), true);
    }

    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }
}
