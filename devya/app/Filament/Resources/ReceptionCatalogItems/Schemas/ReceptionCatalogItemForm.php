<?php

namespace App\Filament\Resources\ReceptionCatalogItems\Schemas;

use App\Models\ReceptionBill;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReceptionCatalogItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Catalog Details')
                    ->description('Manage the item name, type, and billing prices. Inactive items cannot be added to new bills.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Item / Service Name')
                            ->required()
                            ->maxLength(191)
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g. Koththamalli'),

                        Select::make('category')
                            ->label('Category')
                            ->options(ReceptionBill::CATEGORIES)
                            ->default('other')
                            ->required()
                            ->native(false),

                        TextInput::make('unit_price_lkr')
                            ->label('Price (LKR)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(9999999999.99)
                            ->step(0.01)
                            ->prefix('LKR'),

                        TextInput::make('unit_price_usd')
                            ->label('Price (USD)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(9999999999.99)
                            ->step(0.01)
                            ->prefix('USD'),

                        Toggle::make('is_active')
                            ->label('Available for new bills')
                            ->default(true)
                            ->helperText('Turn off to hide this item from Reception Billing without deleting it.'),
                    ])
                    ->columns(2),
            ]);
    }
}
