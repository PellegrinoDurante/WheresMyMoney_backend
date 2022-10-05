<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChargeResource\Pages;
use App\Models\Charge;
use Exception;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class ChargeResource extends Resource
{
    protected static ?string $model = Charge::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('recurring_expense_id')
                    ->relationship('recurringExpense', 'name')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->mask(fn(Forms\Components\TextInput\Mask $mask) => $mask->money(prefix: '€', isSigned: false))
                    ->required(),
                Forms\Components\DatePicker::make('charged_at')
                    ->required(),
                Forms\Components\Toggle::make('draft')
                    ->required(),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('recurringExpense.name')
                    ->label(__('recurring_expense.title'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('charge.amount'))
                    ->money('eur', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('charged_at')
                    ->label(__('charge.charged_at'))
                    ->date()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('draft')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('recurring_expense_id')
                    ->label(__('recurring_expense.title'))
                    ->relationship('recurringExpense', 'name'),
                Tables\Filters\Filter::make('amount')
                    ->form([
                        Forms\Components\TextInput::make('amount_from')
                            ->label(__('charge.amount_from'))
                            ->mask(fn(Forms\Components\TextInput\Mask $mask) => $mask->money(prefix: '€', isSigned: false)),
                        Forms\Components\TextInput::make('amount_to')
                            ->label(__('charge.amount_to'))
                            ->mask(fn(Forms\Components\TextInput\Mask $mask) => $mask->money(prefix: '€', isSigned: false))
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['amount_from'],
                                fn(Builder $builder, $value) => $builder->where('amount', '>=', $value * 100)
                            )
                            ->when(
                                $data['amount_to'],
                                fn(Builder $builder, $value) => $builder->where('amount', '<=', $value * 100)
                            );
                    }),
                Tables\Filters\Filter::make('draft')
                    ->label(__('charge.draft'))
                    ->query(fn(Builder $query) => $query->where('draft', '=', true)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ManageCharges::route('/'),
        ];
    }
}
