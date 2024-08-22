<?php

namespace App\Traits;

trait EnumToArray
{
    /**
     * @return array
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @param string $currency
     * @return bool
     */
    public static function key(string $currency): bool
    {
        return in_array($currency, self::array(), true);
    }

    /**
     * @return array
     */
    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }
}
