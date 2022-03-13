<?php

namespace App\Services\ChargeDataProvider;

use Carbon\Carbon;

class UserDefinedChargeDataProvider implements ChargeDataProvider
{
    const TYPE = "user_defined";

    public function __construct(private float $amount, private ?Carbon $chargedAt = null)
    {
    }

    function getData(?object $context = null): ChargeData
    {
        // Returns the user-defined charge data (fixed value for every charge)
        $chargedAt = $this->chargedAt ?? now();
        return new ChargeData($this->amount, $chargedAt);
    }
}
