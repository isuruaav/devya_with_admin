<?php

namespace App\Filament\Resources\ReceptionBills\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReceptionBillInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bill Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('bill_number')->label('Bill Number'),
                        TextEntry::make('created_at')->label('Date')->dateTime('d M Y, h:i A'),
                        TextEntry::make('customer')
                            ->label('Customer')
                            ->state(fn ($record): string => $record->patient?->full_name
                                ?: 'Walk-in sale'),
                        TextEntry::make('payment_status')->label('Payment Status')->badge(),
                        TextEntry::make('subtotal')->label('Subtotal')->money(fn ($record): string => $record->currency),
                        TextEntry::make('discount')->label('Discount')->money(fn ($record): string => $record->currency),
                        TextEntry::make('grand_total')->label('Grand Total')->money(fn ($record): string => $record->currency),
                        TextEntry::make('amount_received')->label('Amount Received')->money(fn ($record): string => $record->currency),
                        TextEntry::make('change_amount')->label('Change')->money(fn ($record): string => $record->currency),
                        TextEntry::make('balance_due')->label('Balance Due')->money(fn ($record): string => $record->currency),
                        TextEntry::make('payment_method')->label('Payment Method')->placeholder('-'),
                        TextEntry::make('receipt_number')->label('Receipt Number')->placeholder('-'),
                        TextEntry::make('items')
                            ->label('Items')
                            ->state(
                                fn ($record): string => $record->items
                                    ->map(fn ($item): string => sprintf(
                                        '%s — %s × %s %s (%s %s)',
                                        ucfirst(str_replace('_', ' ', $item->category)),
                                        $item->description,
                                        rtrim(rtrim(number_format((float) $item->quantity, 2, '.', ''), '0'), '.'),
                                        $record->currency,
                                        $record->currency,
                                        number_format((float) $item->total, 2)
                                    ))
                                    ->implode("\n")
                            )
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
