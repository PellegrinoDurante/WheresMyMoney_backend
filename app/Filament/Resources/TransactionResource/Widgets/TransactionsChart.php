<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Transaction;
use Filament\Widgets\PieChartWidget;

class TransactionsChart extends PieChartWidget
{
    protected static ?string $heading = 'Per categoria';

    protected function getData(): array
    {
        $data = Transaction::selectRaw('coalesce(transaction_categories.name, "Senza categoria") as category, sum(-amount) / 100 as category_amount')
            ->where('amount', '<', 0)
            ->leftJoin('transaction_categories', 'transactions.category_id', '=', 'transaction_categories.id')
            ->groupBy('category_id')->get();

        return [
            'datasets' => [
                [
                    'label' => 'AAAA',
                    'data' => $data->pluck('category_amount'),
                ],
            ],
            'labels' => $data->pluck('category'),
        ];
    }
}
