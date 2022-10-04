<?php

namespace App\Services;

use App\Models\Charge;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class ChargeService
{
    public function getMonthAverage(CarbonInterface $relativeTo = null): float
    {
        $relativeTo = ($relativeTo ?? now())->toImmutable();

        return Charge::whereDate('charged_at', '>=', $relativeTo->subYear())
                ->whereDate('charged_at', '<=', $relativeTo)
                ->sum('amount') / 100 / 12;
    }

    public function getAverageOfLastYear(CarbonInterface $relativeTo = null): Collection
    {
        $relativeTo = ($relativeTo ?? now())->toImmutable();
        $period = $relativeTo->subYear()->toPeriod($relativeTo, '1 month');

        $stats = [];
        foreach ($period as $date) {
            $stats[] = [
                'date' => $date->format('Y-m'),
                'value' => $this->getMonthAverage($date),
            ];
        }

        return collect($stats);
    }
}
