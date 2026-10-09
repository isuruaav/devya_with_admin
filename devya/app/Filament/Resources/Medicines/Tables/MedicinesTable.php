<?php

namespace App\Filament\Resources\Medicines\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MedicinesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withSum([
                'consultationMedicines as used_quantity' => fn (Builder $query) => $query
                    ->whereHas('consultation'),
            ], 'quantity'))
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Medicine Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->searchable(),

                TextColumn::make('stock_quantity')
                    ->label('Stock Quantity')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($record) => $record->stock_quantity <= $record->reorder_level ? 'danger' : 'success'),

                TextColumn::make('used_quantity')
                    ->label('Used')
                    ->numeric()
                    ->default(0)
                    ->sortable(),

                TextColumn::make('remaining_stock')
                    ->label('Remaining')
                    ->state(fn ($record): int => (int) $record->stock_quantity)
                    ->numeric()
                    ->sortable()
                    ->color(fn ($record) => $record->stock_quantity <= $record->reorder_level ? 'danger' : 'success'),

                TextColumn::make('unit_price')
                    ->label('Selling Price')
                    ->money('LKR')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
