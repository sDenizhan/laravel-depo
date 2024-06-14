<?php
namespace App\Enums;

enum Currency: int
{
    case USD = 1;
    case EUR = 2;
    case GBP = 3;
    case TRY = 4;

    public static function toArray()
    {
        return collect(self::cases())->mapWithKeys(fn($value, $key) => [$key => $value]);
    }
}
