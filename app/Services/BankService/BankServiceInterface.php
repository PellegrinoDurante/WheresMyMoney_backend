<?php

namespace App\Services\BankService;

use App\Models\AccessToken;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface BankServiceInterface
{
    public function getBanksList(): Collection;
    public function getBalance(AccessToken $accessToken): float;
    public function getTransactions(AccessToken $accessToken, Carbon $dateFrom, Carbon $dateTo): Collection;
}
