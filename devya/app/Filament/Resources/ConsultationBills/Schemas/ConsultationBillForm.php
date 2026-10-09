<?php

namespace App\Filament\Resources\ConsultationBills\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ConsultationBillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->extraAttributes(['class' => 'admin-form consultation-bill-form'])
            ->components([

                /*
                |--------------------------------------------------------------------------
                | Bill Information
                |--------------------------------------------------------------------------
                */

                Section::make('Bill Information')
                    ->contained(false)
                    ->schema([

                        TextInput::make('bill_number')
                            ->label('Bill Number')
                            ->disabled(),

                        TextInput::make(
                            'consultation.consultation_number'
                        )
                            ->label('Consultation Number')
                            ->disabled(),

                        TextInput::make(
                            'currency'
                        )
                            ->label('Currency')
                            ->disabled(),

                        TextInput::make(
                            'payment_status'
                        )
                            ->label('Payment Status')
                            ->disabled(),

                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                /*
                |--------------------------------------------------------------------------
                | Charges
                |--------------------------------------------------------------------------
                */

                Section::make('Consultation Charges')
                    ->contained(false)
                    ->schema([

                        TextInput::make('doctor_fee')
                            ->label('Doctor Fee')
                            ->numeric()
                            ->disabled(),

                        TextInput::make('treatment_total')
                            ->label('Treatment Total')
                            ->numeric()
                            ->disabled(),

                        TextInput::make('medicine_total')
                            ->label('Medicine Total')
                            ->numeric()
                            ->disabled(),

                        TextInput::make('grand_total')
                            ->label('Grand Total')
                            ->numeric()
                            ->disabled(),

                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                /*
                |--------------------------------------------------------------------------
                | Payment Details
                |--------------------------------------------------------------------------
                */

                Section::make('Payment Details')
                    ->contained(false)
                    ->schema([

                        TextInput::make(
                            'appointment_paid'
                        )
                            ->label(
                                'Already Paid at Channeling'
                            )
                            ->numeric()
                            ->disabled(),

                        TextInput::make('balance_due')
                            ->label('Balance Due')
                            ->numeric()
                            ->disabled(),

                        TextInput::make(
                            'amount_received'
                        )
                            ->label('Amount Received')
                            ->numeric()
                            ->disabled(),

                        TextInput::make(
                            'change_amount'
                        )
                            ->label('Change')
                            ->numeric()
                            ->disabled(),

                        TextInput::make(
                            'payment_method'
                        )
                            ->label('Payment Method')
                            ->disabled(),

                        TextInput::make(
                            'payment_reference'
                        )
                            ->label('Payment Reference')
                            ->disabled(),

                        TextInput::make(
                            'receipt_number'
                        )
                            ->label('Receipt Number')
                            ->disabled(),

                    ])
                    ->columns(['default' => 1, 'md' => 2]),

            ]);
    }
}
