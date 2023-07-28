<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Transaction;
use Filament\Forms\Components\Select;
use Illuminate\Database\Query\JoinClause;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class CategoriesChart extends ApexChartWidget
{
    protected static string $chartId = 'transactions_spent_per_categories';

    protected function getHeading(): ?string
    {
        return __('transactions.stats.spent_per_categories_of_month');
    }

    protected function getOptions(): array
    {
        $month = $this->filterFormData['month'] ?? 'current';
        $monthDate = $month == 'current' ? now() : now()->subMonth();

        $data = Transaction::selectRaw('coalesce(transaction_categories.name, "Senza categoria") as category, sum(-amount) / 100 as category_amount')
            ->where('amount', '<', 0)
            ->whereDate('spent_at', '>=', $monthDate->startOfMonth())
            ->whereDate('spent_at', '<', $monthDate->addMonth()->startOfMonth())
            ->leftJoin('transaction_categories', function (JoinClause $join) {
                $join->on('transactions.category_id', '=', 'transaction_categories.id')
                    ->orOn(function (JoinClause $query) {
                        $query->on('transactions.guessed_category_id', '=', 'transaction_categories.id')
                            ->whereNull('transactions.category_id');
                    });
            })
            ->groupBy('category')
            ->get();

        return [
            'chart' => [
                'type' => 'pie',
            ],
            'series' => $data->pluck('category_amount')->map(fn($v) => (float)$v)->toArray(),
            'labels' => $data->pluck('category')->toArray(),
            'legend' => [
                'onItemClick' => [
                    'toggleDataSeries' => true,
                ],
            ],
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('month')
                ->label(__('month'))
                ->options([
                    'current' => 'Corrente',
                    'last' => 'Scorso mese'
                ])
                ->required()
                ->default('current'),

        ];
    }
}
