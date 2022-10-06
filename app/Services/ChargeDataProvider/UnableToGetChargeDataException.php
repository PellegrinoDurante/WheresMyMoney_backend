<?php

namespace App\Services\ChargeDataProvider;

use Exception;

class UnableToGetChargeDataException extends Exception
{
    public function __construct()
    {
        parent::__construct('Unable to read charge data.');
    }
}
