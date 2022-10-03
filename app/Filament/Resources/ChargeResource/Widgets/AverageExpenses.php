<?php

namespace App\Filament\Resources\ChargeResource\Widgets;

use Akaunting\Money\Money;
use App\Services\ChargeService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class AverageExpenses extends BaseWidget
{
    protected function getCards(): array
    {
        $chargeService = app(ChargeService::class);

        $averageExpenses = $chargeService->getMonthAverage();
        $lastAverageExpense = $chargeService->getMonthAverage(now()->subMonth());
        $increased = $averageExpenses > $lastAverageExpense;
        $changePercentage = ($averageExpenses - $lastAverageExpense) / $lastAverageExpense * 100;

        return [
            Card::make('Average monthly expenses', Money::EUR($averageExpenses, true))
                ->description(sprintf('%d%% %s from last month', $changePercentage, $increased ? 'increase' : 'decrease'))
                ->descriptionIcon($increased ? 'heroicon-s-trending-up' : 'heroicon-s-trending-down')
                ->descriptionColor($increased ? 'danger' : 'success'),
        ];
    }
}
