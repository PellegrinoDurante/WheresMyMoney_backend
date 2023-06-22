<?php

namespace App\Casts;

use Akaunting\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class MoneyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     */
    public function get($model, string $key, $value, array $attributes): ?Money
    {
        return $value == null ? null : Money::EUR($value);
    }

    /**
     * Prepare the given value for storage.
     */
    public function set($model, string $key, $value, array $attributes)
    {
        /** @var ?Money $value */
        return $value == null ? null : intval($value->getAmount());
    }
}
