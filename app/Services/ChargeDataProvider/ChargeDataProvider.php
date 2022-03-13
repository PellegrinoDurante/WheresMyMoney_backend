<?php

namespace App\Services\ChargeDataProvider;

interface ChargeDataProvider
{
    function getData(?object $context = null): ChargeData;
}
