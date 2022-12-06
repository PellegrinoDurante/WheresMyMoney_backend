<?php

namespace App\Filament\Resources\WalletResource\Pages;

use App\Filament\Resources\WalletResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageWallets extends ManageRecords
{
    protected static string $resource = WalletResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
