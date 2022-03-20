<?php

namespace App\Services\ChargeDataProvider;

interface ChargeDataProvider
{
    /**
     * @param object|null $context
     * @return ChargeData
     * @throws UnableToGetChargeDataException
     */
    function getData(?object $context = null): ChargeData;
}
