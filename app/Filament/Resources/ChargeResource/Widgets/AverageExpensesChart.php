<?php

namespace App\Filament\Resources\ChargeResource\Widgets;

use App\Services\ChargeService;
use Filament\Widgets\LineChartWidget;

class AverageExpensesChart extends LineChartWidget
{
    protected static ?string $heading = 'Average Monthly Expenses';

    protected function getData(): array
    {
        $chargeService = app(ChargeService::class);

        $stats = $chargeService->getAverageOfLastYear();

        return [
            'datasets' => [
                [
                    'label' => 'Average monthly expenses',
                    'data' => $stats->pluck('value')->toArray(),
                ],
            ],
            'labels' => $stats->pluck('date')->toArray(),
        ];
    }
}
