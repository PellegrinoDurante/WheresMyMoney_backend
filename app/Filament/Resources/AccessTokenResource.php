<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessTokenResource\Pages;
use App\Models\AccessToken;
use App\Services\BankService\NordigenService;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\HtmlString;

class AccessTokenResource extends Resource
{
    protected static ?string $model = AccessToken::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(128),
            ]);
    }

    public static function table(Table $table): Table
    {
        $nordigenService = app(NordigenService::class);

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('provider'),
                Tables\Columns\TextColumn::make('expires_in'),
            ])
            ->actions([
                Tables\Actions\Action::make('balance')
                    ->action(function () {
                    })
                    ->modalContent(function (AccessToken $record) use ($nordigenService) {
                        return new HtmlString('Saldo: €' . $nordigenService->getBalance($record));
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAccessTokens::route('/'),
        ];
    }
}
