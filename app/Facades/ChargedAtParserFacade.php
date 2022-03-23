<?php

namespace App\Facades;

use App\Services\ChargeDataProvider\ChargedAtParser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Carbon parse(string $chargedAtString, string $dateFormat, ?string $locale = null)
 */
class ChargedAtParserFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ChargedAtParser::class;
    }
}
