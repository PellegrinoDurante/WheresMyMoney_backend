<?php

namespace App\Services\Stats;

use App\Models\Transaction;
use App\Services\TransactionService;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

class StatisticsService
{
    public function __construct(private readonly TransactionService $transactionService)
    {
    }

    public function getTransactionsTotalSpent(CarbonInterface $from, CarbonInterface $to, Frequency $frequency, $categories): array
    {
        $data = Transaction::query()
            ->negative()
            ->inCategories($categories)
            ->notInCategory(8)
            ->whereDate('spent_at', '>=', $from)
            ->whereDate('spent_at', '<', $to)
            ->selectRaw('SUM(-amount/100) AS spent_amount')
            ->selectRaw($this->getFrequencySelect($frequency))
            ->groupBy('spent_date')
            ->orderBy('spent_date')
            ->get();

        $period = $this->getPeriod($from, $to, $frequency);

        $stats = $period->map(function ($date) use ($data) {
            $dateKey = $date->format('Y-m');

            return [
                'date' => $dateKey,
                'value' => (float)$data->firstWhere('spent_date', $dateKey)?->spent_amount ?? 0,
            ];
        });

        return iterator_to_array($stats);
    }

    public function getTransactionsAverageSpent(CarbonInterface $from, CarbonInterface $to, Frequency $frequency, $categories): array
    {
        $period = $this->getPeriod($from, $to, $frequency);

        $stats = $period->map(function ($date) use ($categories) {
            return [
                'date' => $date->format('Y-m'),
                'value' => $this->transactionService->getMonthAverageSpent($date, categories: $categories)->getValue(),
            ];
        });

        return iterator_to_array($stats);
    }

    private function getPeriod(CarbonInterface $from, CarbonInterface $to, Frequency $frequency): CarbonPeriod
    {
        return match ($frequency) {
            Frequency::Monthly => $from->toPeriod($to, '+1 month'),
        };
    }

    private function getFrequencySelect(Frequency $frequency): string
    {
        return match ($frequency) {
            Frequency::Monthly => "DATE_FORMAT(spent_at, '%Y-%m') spent_date",
        };
    }
}
