<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->extraAttributes(['class' => 'admin-form expense-form'])
            ->components([
                Section::make('Expense Details')->contained(false)->schema([
                    Select::make('category')->options([
                        'Light Bill' => 'Light Bill',
                        'Water Bill' => 'Water Bill',
                        'Phone Bill' => 'Phone Bill',
                        'Hospital Rental' => 'Hospital Rental',
                        'Salaries' => 'Salaries',
                        'Supplies' => 'Supplies',
                        'Other' => 'Other',
                    ])->searchable()->required(),
                    TextInput::make('amount')->numeric()
                        ->prefix(fn (Get $get): string => $get('currency') === 'USD' ? '$' : 'Rs.')
                        ->required(),
                    Select::make('currency')->options(['LKR' => 'LKR', 'USD' => 'USD'])->default('LKR')->live()->required(),
                    DatePicker::make('expense_date')->default(now())->required(),
                    TextInput::make('description')->maxLength(255)->columnSpanFull(),
                    FileUpload::make('receipt_path')
                        ->label('Bill / Receipt')
                        ->disk('public')
                        ->directory('expenses/receipts')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(5120)
                        ->downloadable()
                        ->openable()
                        ->columnSpanFull(),
                ])->columns(['default' => 1, 'md' => 2]),
            ]);
    }
}
