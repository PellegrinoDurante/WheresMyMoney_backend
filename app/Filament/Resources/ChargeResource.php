<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChargeResource\Pages;
use App\Filament\Resources\ChargeResource\RelationManagers;
use App\Models\Charge;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

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
                    ->required(),
                Forms\Components\DatePicker::make('charged_at')
                    ->required(),
                Forms\Components\Toggle::make('draft')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('recurring_expense_id'),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('charged_at')
                    ->date(),
                Tables\Columns\BooleanColumn::make('draft'),
            ])
            ->filters([
                //
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
