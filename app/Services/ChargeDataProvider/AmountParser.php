<?php

namespace App\Services\ChargeDataProvider;

class AmountParser
{
    /**
     * @param string $amountString
     * @return float
     */
    public function parse(string $amountString): float
    {
        return floatval(str_replace(',', '.', $amountString));
    }
}
