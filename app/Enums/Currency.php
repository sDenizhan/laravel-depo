<?php
namespace App\Enums;

enum Currency
{
    const USD = 1;
    const EUR = 2;
    const GBP = 3;
    const TRY = 4;

    public static function toArray(): \Illuminate\Support\Collection
    {
        return collect(self::cases())->mapWithKeys(fn($value, $key) => [$key => $value]);
    }
}
