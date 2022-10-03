<?php

namespace App\Filament\Resources\RecurringExpenseResource\Pages;

use App\Filament\Resources\RecurringExpenseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRecurringExpense extends ViewRecord
{
    protected static string $resource = RecurringExpenseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
