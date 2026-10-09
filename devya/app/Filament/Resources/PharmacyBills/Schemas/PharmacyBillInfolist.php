<?php

namespace App\Filament\Resources\PharmacyBills\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PharmacyBillInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Bill Information')
                    ->schema([
                        TextEntry::make('bill_number')
                            ->label('Bill No'),

                        TextEntry::make('patient.full_name')
                            ->label('Patient')
                            ->placeholder('Walk-in'),

                        TextEntry::make('currency')
                            ->label('Currency')
                            ->badge(),

                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('Y-m-d H:i'),
                    ])
                    ->columns(4),

                Section::make('Medicines')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                TextEntry::make('medicine_name')
                                    ->label('Medicine'),

                                TextEntry::make('quantity')
                                    ->label('Quantity')
                                    ->numeric(decimalPlaces: 2),

                                TextEntry::make('unit_price')
                                    ->label('Unit Price')
                                    ->numeric(decimalPlaces: 2),

                                TextEntry::make('total')
                                    ->label('Total')
                                    ->numeric(decimalPlaces: 2)
                                    ->weight('bold'),

                                TextEntry::make('issued_quantity')
                                    ->label('Issued')
                                    ->numeric(decimalPlaces: 2),

                                TextEntry::make('issued_at')
                                    ->label('Issued At')
                                    ->dateTime('Y-m-d H:i')
                                    ->placeholder('Not Issued'),

                                TextEntry::make('issuedBy.name')
                                    ->label('Issued By')
                                    ->placeholder('-'),
                            ])
                            ->columns(7)
                            ->contained(false),
                    ]),

                Section::make('Bill Summary')
                    ->schema([
                        TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->numeric(decimalPlaces: 2)
                            ->suffix(fn ($record): string => ' '.$record->currency),

                        TextEntry::make('discount')
                            ->label('Discount')
                            ->numeric(decimalPlaces: 2)
                            ->suffix(fn ($record): string => ' '.$record->currency),

                        TextEntry::make('grand_total')
                            ->label('Grand Total')
                            ->numeric(decimalPlaces: 2)
                            ->weight('bold')
                            ->suffix(fn ($record): string => ' '.$record->currency),

                        TextEntry::make('balance_due')
                            ->label('Balance Due')
                            ->numeric(decimalPlaces: 2)
                            ->weight('bold')
                            ->color(
                                fn ($record): string => (float) $record->balance_due > 0
                                        ? 'danger'
                                        : 'success'
                            )
                            ->suffix(fn ($record): string => ' '.$record->currency),
                    ])
                    ->columns(4),

                Section::make('Payment Information')
                    ->schema([
                        TextEntry::make('payment_status')
                            ->label('Payment Status')
                            ->badge()
                            ->formatStateUsing(
                                fn (?string $state): string => match ($state) {
                                    'paid' => 'Paid',
                                    'partial' => 'Partial',
                                    'cancelled' => 'Cancelled',
                                    default => 'Unpaid',
                                }
                            )
                            ->color(
                                fn (?string $state): string => match ($state) {
                                    'paid' => 'success',
                                    'partial' => 'warning',
                                    'cancelled' => 'danger',
                                    default => 'gray',
                                }
                            ),

                        TextEntry::make('amount_received')
                            ->label('Amount Received')
                            ->numeric(decimalPlaces: 2)
                            ->suffix(fn ($record): string => ' '.$record->currency),

                        TextEntry::make('change_amount')
                            ->label('Change')
                            ->numeric(decimalPlaces: 2)
                            ->suffix(fn ($record): string => ' '.$record->currency),

                        TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->formatStateUsing(
                                fn (?string $state): string => match ($state) {
                                    'cash' => 'Cash',
                                    'card' => 'Card',
                                    'bank_transfer' => 'Bank Transfer',
                                    'online' => 'Online',
                                    default => $state ?? '-',
                                }
                            )
                            ->placeholder('-'),

                        TextEntry::make('payment_reference')
                            ->label('Payment Reference')
                            ->placeholder('-'),

                        TextEntry::make('receipt_number')
                            ->label('Receipt Number')
                            ->placeholder('-'),

                        TextEntry::make('paid_at')
                            ->label('Paid At')
                            ->dateTime('Y-m-d H:i')
                            ->placeholder('-'),
                    ])
                    ->columns(4),

                Section::make('Record Information')
                    ->schema([
                        TextEntry::make('createdBy.name')
                            ->label('Created By')
                            ->placeholder('-'),

                        TextEntry::make('paidBy.name')
                            ->label('Paid By')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('Y-m-d H:i'),
                    ])
                    ->columns(3)
                    ->collapsible(),

            ]);
    }
}
