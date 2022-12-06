<?php

namespace App\Filament\Resources\AccessTokenResource\Pages;

use App\Filament\Resources\AccessTokenResource;
use App\Services\BankService\NordigenService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAccessTokens extends ManageRecords
{
    protected static string $resource = AccessTokenResource::class;

    protected function getActions(): array
    {
        $nordigenService = app(NordigenService::class);

        return [
            Actions\Action::make('link_bank_account')
                ->label('Collega conto bancario')
                ->action(function (array $data) {
                    $this->redirectRoute('auth.redirect', [
                        'driver' => 'nordigen',
                        'institutionId' => $data['bank_institution'],
                        'name' => $data['name'],
                    ]);
                })
                ->form([
                    Select::make('bank_institution')
                        ->label('Banca')
                        ->options(function () use ($nordigenService) {
                            return $nordigenService->getInstitutions()->mapWithKeys(function (array $bank) {
                                return [$bank['id'] => $bank['name']];
                            });
                        })
                        ->searchable(),
                    TextInput::make('name')
                        ->label('Nome conto'),
                ]),
        ];
    }
}
