<?php

namespace App\Listeners;

use App\Events\TransactionUpdating;
use App\Services\TransactionService;

class TrainTransactionCategorization
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private readonly TransactionService $transactionService)
    {
    }

    /**
     * Handle the event.
     *
     * @param TransactionUpdating $event
     * @return void
     */
    public function handle(TransactionUpdating $event): void
    {
        $transaction = $event->transaction;
        \Log::info('category ' . print_r($transaction->category, true));
        \Log::info('guessedCategory ' . print_r($transaction->guessedCategory, true));
        \Log::info('original category_id ' . print_r($transaction->getOriginal('category_id'), true));

        if ($transaction->category != null && $transaction->getOriginal('category_id') == null
        ) {
            $this->transactionService->learnCategorization($transaction);
        }
    }
}
