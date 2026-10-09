<?php

namespace App\Filament\Resources\ConsultationBills\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ConsultationBillInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Consultation Bill')
                    ->schema([
                        TextEntry::make('consultation.consultation_number')
                            ->label('Consultation No')
                            ->weight('bold'),

                        TextEntry::make('bill_number')
                            ->label('Bill No')
                            ->weight('bold'),

                        TextEntry::make('consultation.patient.full_name')
                            ->label('Patient'),

                        TextEntry::make('consultation.doctor.name')
                            ->label('Doctor'),

                        TextEntry::make('consultation.consultation_date')
                            ->label('Consultation Date')
                            ->dateTime('d M Y, h:i A'),
                    ])
                    ->columns(2),

                Section::make('Charges')
                    ->schema([
                        TextEntry::make('doctor_fee')
                            ->label('Doctor Fee')
                            ->numeric(),

                        TextEntry::make('treatment_total')
                            ->label('Treatment Total')
                            ->numeric(),

                        TextEntry::make('grand_total')
                            ->label('Grand Total')
                            ->numeric()
                            ->weight('bold'),

                        TextEntry::make('currency')
                            ->label('Currency'),
                    ])
                    ->columns(2),

                Section::make('Medicines')
                    ->schema([
                        TextEntry::make('consultation.medicines')
                            ->label('Prescribed Medicines')
                            ->state(function ($record) {
                                $medicines = $record
                                    ->consultation
                                    ?->medicines()
                                    ->with('medicine')
                                    ->get();

                                if ($medicines->isEmpty()) {
                                    return 'No medicines prescribed.';
                                }

                                return $medicines
                                    ->map(function ($item) {
                                        $medicineName =
                                            $item->medicine->name
                                            ?? 'Unknown Medicine';

                                        $quantity =
                                            $item->quantity
                                            ?? 0;

                                        return $medicineName
                                            .' × '
                                            .$quantity;
                                    })
                                    ->implode("\n");
                            })
                            ->html(false)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Payment')
                    ->schema([
                        TextEntry::make('appointment_paid')
                            ->label('Already Paid at Appointment')
                            ->numeric(),

                        TextEntry::make('balance_due')
                            ->label('Balance Due')
                            ->numeric()
                            ->weight('bold'),

                        TextEntry::make('payment_status')
                            ->label('Payment Status')
                            ->badge(),

                        TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->placeholder('-'),

                        TextEntry::make('payment_reference')
                            ->label('Payment Reference')
                            ->placeholder('-'),

                        TextEntry::make('amount_received')
                            ->label('Amount Received')
                            ->numeric()
                            ->placeholder('-'),

                        TextEntry::make('change_amount')
                            ->label('Change')
                            ->numeric()
                            ->placeholder('-'),

                        TextEntry::make('receipt_number')
                            ->label('Receipt No')
                            ->placeholder('-'),

                        TextEntry::make('paid_at')
                            ->label('Paid At')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2),

                Section::make('System Information')
                    ->schema([
                        TextEntry::make('created_by_user_id')
                            ->label('Created By')
                            ->numeric()
                            ->placeholder('-'),

                        TextEntry::make('paid_by_user_id')
                            ->label('Paid By')
                            ->numeric()
                            ->placeholder('-'),

                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),

                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime(),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
