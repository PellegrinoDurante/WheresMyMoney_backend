<?php

namespace App\Services\BankService;

use App\Models\AccessToken;
use App\Models\Transaction;
use Carbon\Carbon;

abstract class BankServiceAbstract implements BankServiceInterface
{
    public function syncTransactions(AccessToken $accountAccessToken)
    {
        $bankTransactions = $this->getTransactions($accountAccessToken, $this->getMinDateAllowed($accountAccessToken), now());
        $bankTransactions->each(function (array $data) use ($accountAccessToken) {
            $transactionId = $data['internalTransactionId'] ?? $data['transactionId'] ?? null;
            $merchantName = $data['creditorName'] ?? $data['debtorName'] ?? null;
            $remittanceInformation = $data['remittanceInformationUnstructured'] ?? null;

            if (Transaction::where('metadata->transactionId', '=', $transactionId)->exists()) {
                return;
            }

            Transaction::create([
                'amount' => $data['transactionAmount']['amount'] * 100,
                'spent_at' => Carbon::parse($data['bookingDate']),
                'wallet_id' => $accountAccessToken->wallet->id,
                'metadata' => [
                    'transactionId' => $transactionId,
                    'merchantName' => $merchantName,
                    'remittanceInformation' => $remittanceInformation,
                ],
            ]);
        });
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
