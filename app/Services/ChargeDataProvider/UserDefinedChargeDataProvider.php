<?php

namespace App\Services\ChargeDataProvider;

use Illuminate\Support\Carbon;
use JetBrains\PhpStorm\Pure;

class UserDefinedChargeDataProvider implements ChargeDataProvider
{
    const TYPE = "user_defined";

    public function __construct(private float $amount, private Carbon $chargedAt)
    {
    }

    #[Pure]
    function getData(object $context): ChargeData
    {
        // Returns the user-defined charge data (fixed value for every charge)
        return new ChargeData($this->amount, $this->chargedAt);
    }
}
