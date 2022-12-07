<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->required(),
                Forms\Components\DateTimePicker::make('spent_at')
                    ->required(),
                Forms\Components\TextInput::make('metadata')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('spent_at')
                    ->label(__('transactions.spent_at'))
                    ->date(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('transactions.amount'))
                    ->money('EUR'),
                Tables\Columns\BadgeColumn::make('type')
                    ->getStateUsing(fn(Transaction $record): string => $record->amount >= 0 ? 'Entrata' : 'Uscita')
                    ->label(__('transactions.type'))
                    ->colors([
                        'success' => fn($state) => $state === __('transactions.type_income'),
                        'danger' => fn($state) => $state === __('transactions.type_outcome'),
                    ]),
                Tables\Columns\TextColumn::make('wallet.name')
                    ->label(__('transactions.wallet'))
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
            'index' => Pages\ManageTransactions::route('/'),
        ];
    }
}
