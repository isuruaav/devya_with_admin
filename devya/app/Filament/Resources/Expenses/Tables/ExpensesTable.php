<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('category')->badge()->searchable()->sortable(),
            TextColumn::make('amount')->money(fn ($record) => $record->currency)->sortable(),
            TextColumn::make('currency')->sortable(),
            TextColumn::make('expense_date')->date()->sortable(),
            TextColumn::make('description')->limit(40),
        ])->actions([
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ])->bulkActions([
            BulkActionGroup::make([DeleteBulkAction::make()]),
        ])->defaultSort('expense_date', 'desc');
    }
}
