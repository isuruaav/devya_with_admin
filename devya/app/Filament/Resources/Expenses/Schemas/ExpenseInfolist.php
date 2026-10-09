<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExpenseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Expense Details')->schema([
                TextEntry::make('category')->badge(),
                TextEntry::make('amount')->money(fn ($record) => $record->currency),
                TextEntry::make('expense_date')->date(),
                TextEntry::make('description')->placeholder('-'),
                TextEntry::make('receipt_path')
                    ->label('Receipt')
                    ->formatStateUsing(fn ($state) => $state ? 'View receipt' : 'No receipt')
                    ->url(fn ($state) => $state ? asset('storage/'.$state) : null)
                    ->openUrlInNewTab(),
            ])->columns(2),
        ]);
    }
}
