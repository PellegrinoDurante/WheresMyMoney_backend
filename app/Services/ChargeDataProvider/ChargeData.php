<?php

namespace App\Services\ChargeDataProvider;

use Carbon\Carbon;

class ChargeData
{

    public function __construct(
        public float $amount,
        public Carbon $chargedAt,
    )
    {
    }
}
