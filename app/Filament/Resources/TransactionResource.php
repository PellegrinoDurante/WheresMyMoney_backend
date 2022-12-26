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

    public static function getModelLabel(): string
    {
        return __('transactions.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('transactions.title_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->label(__('transactions.amount'))
                    ->mask(fn(Forms\Components\TextInput\Mask $mask) => $mask->money(prefix: '€', isSigned: false))
                    ->required(),
                Forms\Components\DateTimePicker::make('spent_at')
                    ->label(__('transactions.spent_at'))
                    ->required(),
                Forms\Components\Select::make('category_id')
                    ->label(__('transactions.category'))
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\Textarea::make('metadata')
                    ->label(__('transactions.metadata'))
                    ->disabled()
                    ->dehydrated(false)
                    ->afterStateHydrated(function (Forms\Components\Textarea $component, $state) {
                        $component->state(json_encode($state));
                    }),
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
                    ->money('EUR', true),
                Tables\Columns\BadgeColumn::make('type')
                    ->label(__('transactions.type'))
                    ->getStateUsing(fn(Transaction $record): string => $record->amount >= 0 ? 'Entrata' : 'Uscita')
                    ->colors([
                        'success' => fn($state) => $state === __('transactions.type_income'),
                        'danger' => fn($state) => $state === __('transactions.type_outcome'),
                    ]),
                Tables\Columns\BadgeColumn::make('category.name')
                    ->label(__('transactions.category')),
                Tables\Columns\TextColumn::make('wallet.name')
                    ->label(__('transactions.wallet'))
            ])
            ->defaultSort('spent_at', 'desc')
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
