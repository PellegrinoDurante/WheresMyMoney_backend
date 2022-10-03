<?php

namespace App\Filament\Resources\RecurringExpenseResource\Pages;

use App\Filament\Resources\RecurringExpenseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecurringExpenses extends ListRecords
{
    protected static string $resource = RecurringExpenseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
