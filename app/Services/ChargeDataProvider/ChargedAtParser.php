<?php

namespace App\Services\ChargeDataProvider;

use Carbon\Carbon;

class ChargedAtParser
{
    /**
     * @param string $chargedAtString
     * @param string $dateFormat
     * @param string|null $locale
     * @return Carbon
     * @throws DataParseFailedException
     */
    public function parse(string $chargedAtString, string $dateFormat, ?string $locale = null): Carbon
    {
        $chargedAt = Carbon::createFromLocaleIsoFormat($dateFormat, $locale ?? 'en', $chargedAtString);

        if (!$chargedAt) {
            throw new DataParseFailedException();
        }

        return $chargedAt;
    }
}
