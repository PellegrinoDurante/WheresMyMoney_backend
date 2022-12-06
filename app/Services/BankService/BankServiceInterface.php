<?php

namespace App\Services\BankService;

use App\Models\AccessToken;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface BankServiceInterface
{
    public function getInstitutions(): Collection;

    public function getBalance(AccessToken $accountAccessToken): float;

    public function getTransactions(AccessToken $accountAccessToken, Carbon $dateFrom, Carbon $dateTo): Collection;

    public function getMinDateAllowed(AccessToken $accountAccessToken): Carbon;
}
