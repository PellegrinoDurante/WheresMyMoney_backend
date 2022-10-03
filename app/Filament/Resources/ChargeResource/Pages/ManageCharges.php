<?php

namespace App\Filament\Resources\ChargeResource\Pages;

use App\Filament\Resources\ChargeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCharges extends ManageRecords
{
    protected static string $resource = ChargeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
