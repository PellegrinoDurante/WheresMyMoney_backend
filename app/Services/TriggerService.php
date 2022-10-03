<?php

namespace App\Services;

use App\Models\OwnedByUserScope;
use App\Models\RecurringExpense;
use Illuminate\Support\Collection;

class TriggerService
{
    public function getExpenseWithSchedulingTriggers(): Collection
    {
        return RecurringExpense::withoutGlobalScope(OwnedByUserScope::class)
            ->whereIn('trigger->type', ["temporal", "email"])->get();
    }
}
