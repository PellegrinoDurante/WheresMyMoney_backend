<?php

namespace App\Facades;

use App\Services\ChargeDataProvider\AmountParser;
use Illuminate\Support\Facades\Facade;

/**
 * @method static float parse(string $amountString)
 */
class AmountParserFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AmountParser::class;
    }
}
