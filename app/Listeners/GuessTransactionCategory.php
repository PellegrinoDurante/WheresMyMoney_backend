<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use App\Services\TransactionService;

class GuessTransactionCategory
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
     * @param \App\Events\TransactionCreated $event
     * @return void
     */
    public function handle(TransactionCreated $event)
    {
        $transaction = $event->transaction;

        if ($transaction->category == null && !empty($transaction->metadata['remittanceInformation'])) {
            $guessedCategory = $this->transactionService->guessCategory($transaction);

            if ($guessedCategory != null) {
                $transaction->guessedCategory()->associate($guessedCategory);
                $transaction->save();
            }
        }
    }
}
