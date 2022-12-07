<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait EnumValues
{
    public static function values(): Collection
    {
        return collect(self::cases())->pluck('value');
    }
}
