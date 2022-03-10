<?php

namespace App\Services\ChargeDataProvider;

use Exception;
use JetBrains\PhpStorm\Pure;

class UnsupportedChargeDataProviderTypeException extends Exception
{

    #[Pure]
    public function __construct(string $type)
    {
        parent::__construct(sprintf("Unsupported charge data provider type: %s", $type));
    }
}
