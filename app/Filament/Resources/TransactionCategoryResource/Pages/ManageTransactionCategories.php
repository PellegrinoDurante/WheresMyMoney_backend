<?php

namespace App\Filament\Resources\TransactionCategoryResource\Pages;

use App\Filament\Resources\TransactionCategoryResource;
use Exception;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTransactionCategories extends ManageRecords
{
    protected static string $resource = TransactionCategoryResource::class;

    /**
     * @throws Exception
     */
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
