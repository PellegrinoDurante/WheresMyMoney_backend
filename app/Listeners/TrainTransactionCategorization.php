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

        if ($transaction->category != null && $transaction->getOriginal('category_id') == null
        ) {
            $this->transactionService->learnCategorization($transaction);
        }
    }
}
