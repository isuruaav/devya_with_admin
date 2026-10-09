<?php

namespace App\Filament\Resources\PharmacyBills\Tables;

use App\Mail\PharmacyInvoiceMail;
use App\Models\PharmacyBill;
use App\Support\OutboundMail;
use App\Support\WhatsAppNumber;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class PharmacyBillsTable
{
    public static function getWhatsAppUrl(PharmacyBill $bill): ?string
    {

        $phoneNumber = WhatsAppNumber::normalize(

            $bill->patient?->whatsapp_number ?: $bill->patient?->phone_number

        );

        if (! $phoneNumber || $bill->payment_status === 'cancelled') {

            return null;

        }

        $shareUrl = URL::temporarySignedRoute(

            'pharmacy-bills.shared',

            now()->addDays(30),

            ['pharmacyBill' => $bill->getKey()]

        );

        $message = implode("\n", [

            'ISD Tech Hub (Pvt) Ltd pharmacy bill',

            'Bill No: '.$bill->bill_number,

            'Patient: '.($bill->patient->full_name ?? 'Patient'),

            'Total: '.$bill->currency.' '.number_format((float) $bill->grand_total, 2),

            'View / print the 80mm bill: '.$shareUrl,

        ]);

        return 'https://wa.me/'.$phoneNumber.'?text='.rawurlencode($message);

    }

    public static function configure(Table $table): Table
    {

        return $table

            ->columns([

                TextColumn::make('bill_number')

                    ->label('Bill No')

                    ->searchable()

                    ->sortable(),

                TextColumn::make('patient.full_name')

                    ->label('Patient')

                    ->searchable()

                    ->sortable()

                    ->placeholder('Walk-in'),

                TextColumn::make('subtotal')

                    ->label('Subtotal')

                    ->numeric(decimalPlaces: 2)

                    ->sortable()

                    ->suffix(

                        fn (PharmacyBill $record): string => ' '.$record->currency

                    ),

                TextColumn::make('discount')

                    ->label('Discount')

                    ->numeric(decimalPlaces: 2)

                    ->sortable()

                    ->suffix(

                        fn (PharmacyBill $record): string => ' '.$record->currency

                    ),

                TextColumn::make('grand_total')

                    ->label('Grand Total')

                    ->numeric(decimalPlaces: 2)

                    ->sortable()

                    ->weight('bold')

                    ->suffix(

                        fn (PharmacyBill $record): string => ' '.$record->currency

                    ),

                TextColumn::make('amount_received')

                    ->label('Received')

                    ->numeric(decimalPlaces: 2)

                    ->sortable()

                    ->suffix(

                        fn (PharmacyBill $record): string => ' '.$record->currency

                    ),

                TextColumn::make('change_amount')

                    ->label('Change')

                    ->numeric(decimalPlaces: 2)

                    ->sortable()

                    ->suffix(

                        fn (PharmacyBill $record): string => ' '.$record->currency

                    ),

                TextColumn::make('balance_due')

                    ->label('Balance Due')

                    ->numeric(decimalPlaces: 2)

                    ->sortable()

                    ->weight('bold')

                    ->color(

                        fn (PharmacyBill $record): string => (float) $record->balance_due > 0

                                ? 'danger'

                                : 'success'

                    )

                    ->suffix(

                        fn (PharmacyBill $record): string => ' '.$record->currency

                    ),

                TextColumn::make('payment_status')

                    ->label('Payment Status')

                    ->badge()

                    ->color(

                        fn (string $state): string => match ($state) {

                            'paid' => 'success',

                            'partial' => 'warning',

                            'cancelled' => 'danger',

                            default => 'gray',

                        }

                    )

                    ->formatStateUsing(

                        fn (string $state): string => match ($state) {

                            'paid' => 'Paid',

                            'partial' => 'Partial',

                            'cancelled' => 'Cancelled',

                            default => 'Unpaid',

                        }

                    ),

                TextColumn::make('payment_method')

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

                TextColumn::make('receipt_number')

                    ->label('Receipt No')

                    ->searchable()

                    ->placeholder('-'),

                TextColumn::make('paid_at')

                    ->label('Paid At')

                    ->dateTime('Y-m-d H:i')

                    ->sortable()

                    ->placeholder('-'),

                TextColumn::make('created_at')

                    ->label('Created')

                    ->dateTime('Y-m-d H:i')

                    ->sortable()

                    ->toggleable(

                        isToggledHiddenByDefault: true

                    ),

                TextColumn::make('updated_at')

                    ->label('Updated')

                    ->dateTime('Y-m-d H:i')

                    ->sortable()

                    ->toggleable(

                        isToggledHiddenByDefault: true

                    ),

            ])

            ->filters([

                SelectFilter::make(

                    'payment_status'

                )

                    ->label('Payment Status')

                    ->options([

                        'unpaid' => 'Unpaid',

                        'partial' => 'Partial',

                        'paid' => 'Paid',

                        'cancelled' => 'Cancelled',

                    ]),

                SelectFilter::make(

                    'currency'

                )

                    ->label('Currency')

                    ->options([

                        'LKR' => 'LKR',

                        'USD' => 'USD',

                    ]),

            ])

            ->recordActions([

                /*

                |--------------------------------------------------------------------------

                | VIEW

                |--------------------------------------------------------------------------

                */

                ViewAction::make(),

                Action::make('printBill')

                    ->label('Print Bill')

                    ->icon('heroicon-o-printer')

                    ->color('gray')

                    ->visible(

                        fn (PharmacyBill $record): bool => $record->payment_status !== 'cancelled'

                    )

                    ->url(

                        fn (PharmacyBill $record): string => route(

                            'pharmacy-bills.print',

                            $record

                        )

                    )

                    ->openUrlInNewTab(),

                Action::make('emailInvoice')

                    ->label('Email')

                    ->icon('heroicon-o-envelope')

                    ->color('info')

                    ->disabled(fn (): bool => ! OutboundMail::isEnabled())

                    ->tooltip(fn (): ?string => OutboundMail::isEnabled() ? null : 'Email delivery is not configured.')

                    ->visible(

                        fn (PharmacyBill $record): bool => filled($record->patient?->email)

                            && $record->payment_status !== 'cancelled'

                    )

                    ->action(function (PharmacyBill $record): void {

                        OutboundMail::ensureEnabled();

                        $record->load([

                            'patient',

                            'items.medicine',

                            'paidBy',

                        ]);

                        $email = $record->patient?->email;

                        if (blank($email)) {

                            Notification::make()

                                ->title('Patient email address is missing')

                                ->danger()

                                ->send();

                            return;

                        }

                        $printUrl = URL::temporarySignedRoute(

                            'pharmacy-bills.shared',

                            now()->addDays(30),

                            ['pharmacyBill' => $record->getKey()]

                        );

                        Mail::to($email)->send(

                            new PharmacyInvoiceMail($record, $printUrl)

                        );

                        Notification::make()

                            ->title('Pharmacy invoice sent')

                            ->body("Bill {$record->bill_number} sent to {$email}.")

                            ->success()

                            ->send();

                    }),

                Action::make('whatsAppInvoice')

                    ->label('WhatsApp')

                    ->icon('heroicon-o-chat-bubble-left-right')

                    ->color('success')

                    ->visible(

                        fn (PharmacyBill $record): bool => $record->payment_status !== 'cancelled'

                            && WhatsAppNumber::normalize(

                                $record->patient?->whatsapp_number ?: $record->patient?->phone_number

                            ) !== null

                    )

                    ->url(

                        fn (PharmacyBill $record): string => self::getWhatsAppUrl($record) ?? '#'

                    )

                    ->openUrlInNewTab(),

                /*

                |--------------------------------------------------------------------------

                | PRINT PHARMACY RECEIPT

                |--------------------------------------------------------------------------

                */

                Action::make('printReceipt')

                    ->label('Print')

                    ->icon('heroicon-o-printer')

                    ->color('gray')

                    ->visible(

                        fn (PharmacyBill $record): bool => $record->payment_status === 'paid'

                    )

                    ->url(

                        fn (PharmacyBill $record): string => route(

                            'pharmacy-bills.receipt',

                            $record

                        )

                    )

                    ->openUrlInNewTab(),

                /*

                |--------------------------------------------------------------------------

                | RECEIVE PAYMENT

                |--------------------------------------------------------------------------

                */

                Action::make('receivePayment')

                    ->label('Receive Payment')

                    ->icon('heroicon-o-banknotes')

                    ->color('success')

                    ->visible(

                        fn (PharmacyBill $record): bool => $record->payment_status === 'unpaid'

                            && (float) $record->balance_due > 0

                    )

                    ->form([

                        Select::make('payment_method')

                            ->label('Payment Method')

                            ->options([

                                'cash' => 'Cash',

                                'card' => 'Card',

                                'bank_transfer' => 'Bank Transfer',

                                'online' => 'Online',

                            ])

                            ->required()

                            ->native(false)

                            ->default('cash'),

                        TextInput::make('amount_received')

                            ->label('Amount Received')

                            ->numeric()

                            ->required()

                            ->step(0.01)

                            ->default(

                                fn (PharmacyBill $record): float => (float) $record->balance_due

                            )

                            ->minValue(

                                fn (PharmacyBill $record): float => (float) $record->balance_due

                            )

                            ->live(debounce: 300)

                            ->prefix(

                                fn (PharmacyBill $record): string => $record->currency

                            ),

                        Placeholder::make('change_amount')

                            ->label('Change')

                            ->content(

                                function (

                                    Get $get,

                                    PharmacyBill $record

                                ): string {

                                    $received = (float) (

                                        $get('amount_received') ?? 0

                                    );

                                    $balance = (float) $record->balance_due;

                                    $change = max(

                                        0,

                                        $received - $balance

                                    );

                                    return $record->currency.

                                        ' '.

                                        number_format(

                                            $change,

                                            2

                                        );

                                }

                            ),

                        TextInput::make('payment_reference')

                            ->label('Payment Reference')

                            ->placeholder('Optional')

                            ->maxLength(255),

                    ])

                    ->modalHeading(

                        'Receive Pharmacy Payment'

                    )

                    ->modalDescription(

                        fn (PharmacyBill $record): string => 'Bill: '.

                            $record->bill_number.

                            ' | Balance Due: '.

                            $record->currency.

                            ' '.

                            number_format(

                                (float) $record->balance_due,

                                2

                            )

                    )

                    ->modalSubmitActionLabel(

                        'Confirm Payment'

                    )

                    ->action(

                        function (

                            PharmacyBill $record,

                            array $data

                        ): void {

                            $amountReceived = (float) (

                                $data['amount_received'] ?? 0

                            );

                            $balanceDue = (float) $record->balance_due;

                            if (

                                $amountReceived < $balanceDue

                            ) {

                                Notification::make()

                                    ->title(

                                        'Insufficient Payment'

                                    )

                                    ->body(

                                        'Amount received must be at least '.

                                        $record->currency.

                                        ' '.

                                        number_format(

                                            $balanceDue,

                                            2

                                        )

                                    )

                                    ->danger()

                                    ->send();

                                return;

                            }

                            $record->markAsPaid(

                                $data['payment_method'],

                                $amountReceived,

                                $data['payment_reference'] ?? null

                            );

                            $fresh = $record->fresh();

                            Notification::make()

                                ->title(

                                    'Payment Received Successfully'

                                )

                                ->body(

                                    'Receipt: '.

                                    $fresh->receipt_number.

                                    ' | Received: '.

                                    $fresh->currency.

                                    ' '.

                                    number_format(

                                        (float) $fresh->amount_received,

                                        2

                                    ).

                                    ' | Change: '.

                                    $fresh->currency.

                                    ' '.

                                    number_format(

                                        (float) $fresh->change_amount,

                                        2

                                    )

                                )

                                ->success()

                                ->send();

                        }

                    ),

                /*

                |--------------------------------------------------------------------------

                | EDIT

                |--------------------------------------------------------------------------

                */

                EditAction::make()

                    ->visible(

                        fn (PharmacyBill $record): bool => $record->payment_status === 'unpaid'

                    ),

            ])

            ->toolbarActions([

                BulkActionGroup::make([

                    DeleteBulkAction::make(),

                ]),

            ]);

    }
}
