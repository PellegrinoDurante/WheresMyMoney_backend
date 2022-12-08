<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\AccessToken;
use App\Services\BankService\NordigenService;
use Exception;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTransactions extends ManageRecords
{
    protected static string $resource = TransactionResource::class;

    /**
     * @throws Exception
     */
    protected function getActions(): array
    {
        $bankService = app(NordigenService::class);
        return [
            Actions\Action::make('sync')
                ->label(__('transactions.sync'))
                ->action(function ($data) use ($bankService) {
                    $bankService->syncTransactions(AccessToken::findOrFail($data['bank_account']));
                })
                ->form([
                    Select::make('bank_account')
                        ->label(__('transactions.bank_account'))
                        ->options(
                            AccessToken::where('type', '=', AccessToken::TYPE_BANK)
                                ->where('provider', '=', AccessToken::PROVIDER_BANK)
                                ->get()
                                ->pluck('name', 'id')
                        )
                ]),
            Actions\CreateAction::make(),
        ];
    }
}
