<?php

namespace App\Services\BankService;

interface BankServiceInterface
{
    function getTransactions(): array;
}
