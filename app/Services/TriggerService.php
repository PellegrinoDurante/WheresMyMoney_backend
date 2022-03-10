<?php

namespace App\Services;

use App\Models\RecurringExpense;
use Illuminate\Support\Collection;

class TriggerService
{
    public function getExpenseWithSchedulingTriggers(): Collection
    {
        return RecurringExpense::whereIn('trigger->type', ["temporal", "email"])->get();
    }
}
