<?php

namespace App\Services\BankService;

use Akaunting\Money\Money;
use App\Models\AccessToken;
use App\Models\Transaction;
use App\Services\TransactionService;
use Carbon\Carbon;
use Log;

abstract class BankServiceAbstract implements BankServiceInterface
{
    public function __construct(
        private readonly TransactionService $transactionService,
    )
    {
    }

    public function syncTransactions(AccessToken $accountAccessToken): array
    {
        Log::info("Start transactions sync");

        $newTransactionsCount = 0;
        $newPotentialDuplicates = 0;

        $bankTransactions = $this->getTransactions($accountAccessToken, $this->getMinDateAllowed($accountAccessToken), now());
        $bankTransactions->each(function (array $data) use (&$newTransactionsCount, &$newPotentialDuplicates, $accountAccessToken) {
            $transactionId = $data['internalTransactionId'] ?? null;
            $merchantName = $data['creditorName'] ?? $data['debtorName'] ?? null;
            $remittanceInformation = $data['remittanceInformationUnstructured'] ?? null;

            $transactionData = [
                'amount' => Money::EUR(floatval($data['transactionAmount']['amount']), true),
                'spent_at' => Carbon::parse($data['bookingDate']),
                'wallet_id' => $accountAccessToken->wallet->id,
                'metadata' => [
                    'transactionId' => $transactionId,
                    'merchantName' => $merchantName,
                    'remittanceInformation' => $remittanceInformation,
                ],
            ];

            // Check transaction duplicates
            $duplicatedTransaction = Transaction::where('metadata->transactionId', '=', $transactionId)
                ->where('wallet_id', $accountAccessToken->wallet->id)
                ->first();

            if ($duplicatedTransaction != null) {
                Log::info("A transaction with internal ID " . ($transactionId ?? 'NULL') . ' already exists - updating its data');
                $duplicatedTransaction->update($transactionData);
                return;
            }

            // Create new transaction
            $transaction = Transaction::create($transactionData);
            Log::info("Created transaction with internal ID " . $transactionId ?? 'NULL');
            $newTransactionsCount++;

            // Check if it's a potential duplicate
            $hasPotentialDuplicates = $this->transactionService->getPotentialDuplicates($transaction)->isNotEmpty();

            if ($hasPotentialDuplicates) {
                $newPotentialDuplicates++;
            } else {
                $transaction->update(['duplication_checked' => true]);
            }
        });

        Log::info(sprintf('Transactions sync completed: %d transactions created and %d potential duplicated transactions detected', $newTransactionsCount, $newPotentialDuplicates));

        return [
            'created' => $newTransactionsCount,
            'potentialDuplicates' => $newPotentialDuplicates,
        ];
    }

    protected function isTokenExpired(AccessToken $nordigenAccessToken): bool
    {
        return $nordigenAccessToken->expired_at !== null || $nordigenAccessToken->created_at->addSeconds($nordigenAccessToken->expires_in) <= now();
    }

    protected function expireAccessToken(string $accessToken)
    {
        AccessToken::whereAccessToken($accessToken)->first()->update([
            'expired_at' => now(),
        ]);
    }
}
