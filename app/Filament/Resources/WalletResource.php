<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletResource\Pages;
use App\Models\AccessToken;
use App\Models\Wallet;
use App\Models\WalletType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label(__('wallets.type'))
                    ->required()
                    ->options(function () {
                        return WalletType::values()->mapWithKeys(
                            fn($type) => [
                                $type => __('wallets.type_' . $type),
                            ]
                        );
                    })
                    ->reactive(),
                Forms\Components\Select::make('access_token_id')
                    ->label(__('wallets.access_token'))
                    ->relationship('accessToken', 'name', function (Builder $query) {
                        $query->where('type', '=', AccessToken::TYPE_BANK)
                            ->where('provider', '=', AccessToken::PROVIDER_BANK);
                    })
                    ->hidden(function ($get) {
                        return $get('type') !== WalletType::Bank->value;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('access_token_id'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ManageWallets::route('/'),
        ];
    }
}
