<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\TransactionCategory;
use App\Services\Stats\Frequency;
use App\Services\Stats\StatisticsService;
use Filament\Forms\Components\Select;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TotalSpentChart extends ApexChartWidget
{
    protected static string $chartId = 'transactions_total_spent_per_month';

    private StatisticsService $statisticsService;

    public function boot(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    protected function getHeading(): ?string
    {
        return __('transactions.stats.monthly_spent');
    }

    protected function getOptions(): array
    {
        $categories = $this->filterFormData['categories'] ?? [];

        // TODO abstract filters
        $data = $this->statisticsService->getTransactionsTotalSpent(now()->subYear(), now(), Frequency::Monthly, $categories);
        $averageSpent = $this->statisticsService->getTransactionsAverageSpent(now()->subYear(), now(), Frequency::Monthly, $categories);

        return [
            'chart' => [
                'type' => 'line',
            ],
            'series' => [
                [
                    'name' => __('transactions.stats.spent_sum'),
                    'data' => array_map(fn($d) => [
                        'x' => $d['date'],
                        'y' => $d['value'],
                    ], $data),
                ],
                [
                    'name' => __('transactions.stats.spent_average'),
                    'data' => array_map(fn($d) => [
                        'x' => $d['date'],
                        'y' => $d['value'],
                    ], $averageSpent),
                ],
            ],
            'xaxis' => [
                'type' => 'category'
            ],
            'stroke' => [
                'curve' => 'smooth',
                'width' => 3
            ],
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('categories')
                ->options(TransactionCategory::where('id', '!=', 8)->get()->pluck('name', 'id'))
                ->multiple(),
        ];
    }
}
