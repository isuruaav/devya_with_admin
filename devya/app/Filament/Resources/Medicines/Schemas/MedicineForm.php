<?php

namespace App\Filament\Resources\Medicines\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MedicineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->extraAttributes(['class' => 'admin-form medicine-form'])
            ->components([
                Section::make('Basic Medicine Details')
                    ->contained(false)
                    ->schema([
                        TextInput::make('name')
                            ->label('Medicine Name (ඖෂධයේ නම)')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('code')
                            ->label('Barcode / Item Code (SKU)')
                            ->placeholder('Scan barcode or enter MED-001')
                            ->autofocus()
                            ->unique(ignoreRecord: true),

                        Select::make('category')
                            ->label('Category (වර්ගය)')
                            ->options([
                                'Thailaya' => 'තෙල් (Thailaya)',
                                'Arishta' => 'අරිෂ්ට/ආසව (Arishta/Asava)',
                                'Churna' => 'චූර්ණ (Churna)',
                                'Vati' => 'ගුලි/වටි (Vati/Guli)',
                                'Kashaya' => 'කසාය (Kashaya)',
                                'Syrup' => 'සිරප් (Syrup)',
                                'Other' => 'වෙනත් (Other)',
                            ])
                            ->required()
                            ->searchable(),

                        Select::make('unit')
                            ->label('Measurement Unit (ඒකකය)')
                            ->options([
                                'ml' => 'Milliliters (ml)',
                                'grams' => 'Grams (g)',
                                'tablets' => 'Tablets / Pills',
                                'bottles' => 'Bottles',
                                'packs' => 'Packs',
                                'pcs' => 'Pieces',
                            ])
                            ->default('pcs')
                            ->required(),
                    ])->columns(['default' => 1, 'md' => 2]),

                Section::make('Stock & Pricing Settings')
                    ->contained(false)
                    ->schema([
                        TextInput::make('cost_price')
                            ->label('Cost Price (මිලදී ගත් මිල)')
                            ->numeric()
                            ->prefix('Rs.')
                            ->default(0.00),

                        TextInput::make('unit_price')
                            ->label('Selling Price (විකුණුම් මිල)')
                            ->numeric()
                            ->prefix('Rs.')
                            ->default(0.00)
                            ->required(),

                        TextInput::make('foreign_price')
                            ->label('Foreign Price (USD)')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00)
                            ->nullable(),

                        TextInput::make('stock_quantity')
                            ->label('Current Stock (දැනට ඇති තොගය)')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        TextInput::make('reorder_level')
                            ->label('Reorder Alert Level (අඩු තොග Alert සීමාව)')
                            ->numeric()
                            ->default(10)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Active Status')
                            ->inline(false)
                            ->default(true),
                    ])->columns(['default' => 1, 'md' => 2]),

                Section::make('Additional Information')
                    ->contained(false)
                    ->schema([
                        Textarea::make('description')
                            ->label('Description / Notes (සටහන්)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
