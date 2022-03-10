<?php

namespace App\Services\ChargeDataProvider;

use Illuminate\Support\Carbon;

class UserDefinedChargeDataProvider implements ChargeDataProvider
{
    const TYPE = "user_defined";

    public function __construct(private float $amount, private Carbon $chargedAt)
    {
    }

    function getData(object $context): object
    {
        // TODO: maybe create a new model class for this structure?
        return (object)[
            "amount" => $this->amount,
            "chargedAt" => $this->chargedAt,
        ];
    }
}
