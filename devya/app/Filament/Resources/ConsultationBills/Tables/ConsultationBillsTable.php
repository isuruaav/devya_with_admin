<?php

namespace App\Filament\Resources\ConsultationBills\Tables;

use App\Models\ConsultationBill;
use App\Models\Doctor;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ConsultationBillsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            /*
            |--------------------------------------------------------------------------
            | Columns
            |--------------------------------------------------------------------------
            */

            ->columns([

                TextColumn::make('bill_number')
                    ->label('Bill No')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make(
                    'consultation.consultation_number'
                )
                    ->label('Consultation No')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make(
                    'consultation.patient.full_name'
                )
                    ->label('Patient')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make(
                    'consultation.doctor.name'
                )
                    ->label('Doctor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('doctor_fee')
                    ->label('Doctor Fee')
                    ->formatStateUsing(
                        fn (
                            $state,
                            ConsultationBill $record
                        ): string => $record->currency.
                            ' '.
                            number_format(
                                (float) $state,
                                2
                            )
                    )
                    ->sortable(),

                TextColumn::make('treatment_total')
                    ->label('Treatments')
                    ->formatStateUsing(
                        fn (
                            $state,
                            ConsultationBill $record
                        ): string => $record->currency.
                            ' '.
                            number_format(
                                (float) $state,
                                2
                            )
                    )
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('medicine_total')
                    ->label('Medicines')
                    ->formatStateUsing(
                        fn (
                            $state,
                            ConsultationBill $record
                        ): string => $record->currency.
                            ' '.
                            number_format(
                                (float) $state,
                                2
                            )
                    )
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->formatStateUsing(
                        fn (
                            $state,
                            ConsultationBill $record
                        ): string => $record->currency.
                            ' '.
                            number_format(
                                (float) $state,
                                2
                            )
                    )
                    ->weight('bold')
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('appointment_paid')
                    ->label('Already Paid')
                    ->formatStateUsing(
                        fn (
                            $state,
                            ConsultationBill $record
                        ): string => $record->currency.
                            ' '.
                            number_format(
                                (float) $state,
                                2
                            )
                    )
                    ->color('success')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('balance_due')
                    ->label('Balance Due')
                    ->formatStateUsing(
                        fn (
                            $state,
                            ConsultationBill $record
                        ): string => $record->currency.
                            ' '.
                            number_format(
                                (float) $state,
                                2
                            )
                    )
                    ->weight('bold')
                    ->color(
                        fn ($state): string => (float) $state > 0
                                ? 'danger'
                                : 'success'
                    )
                    ->sortable(),

                TextColumn::make('amount_received')
                    ->label('Amount Received')
                    ->formatStateUsing(
                        fn (
                            $state,
                            ConsultationBill $record
                        ): string => $record->currency.
                            ' '.
                            number_format(
                                (float) $state,
                                2
                            )
                    )
                    ->color('success')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('change_amount')
                    ->label('Change')
                    ->formatStateUsing(
                        fn (
                            $state,
                            ConsultationBill $record
                        ): string => $record->currency.
                            ' '.
                            number_format(
                                (float) $state,
                                2
                            )
                    )
                    ->color('warning')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->color(
                        fn (
                            string $state
                        ): string => match ($state) {
                            'paid' => 'success',
                            'partial' => 'warning',
                            'cancelled' => 'danger',
                            default => 'gray',
                        }
                    )
                    ->formatStateUsing(
                        fn (
                            string $state
                        ): string => match ($state) {
                            'paid' => 'Paid',
                            'partial' => 'Partial',
                            'cancelled' => 'Cancelled',
                            default => 'Unpaid',
                        }
                    )
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->badge()
                    ->formatStateUsing(
                        fn (
                            ?string $state
                        ): string => match ($state) {
                            'cash' => 'Cash',
                            'card' => 'Card',
                            'bank_transfer' => 'Bank Transfer',
                            'online' => 'Online',
                            default => $state
                                ? ucfirst($state)
                                : '-',
                        }
                    )
                    ->toggleable(),

                TextColumn::make('receipt_number')
                    ->label('Receipt No')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('paid_at')
                    ->label('Paid At')
                    ->dateTime('d M Y h:i A')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y h:i A')
                    ->sortable()
                    ->toggleable(
                        isToggledHiddenByDefault: true
                    ),
            ])

            /*
            |--------------------------------------------------------------------------
            | Filters
            |--------------------------------------------------------------------------
            */

            ->filters([

                Filter::make('bill_date')
                    ->label('Consultation Date')
                    ->form([
                        DatePicker::make('date')
                            ->label('Consultation Date')
                            ->default(now()->toDateString())
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->closeOnDateSelection(),
                    ])
                    ->query(
                        function (
                            Builder $query,
                            array $data
                        ): Builder {

                            $date = $data['date'] ?? null;

                            if (! $date) {
                                return $query;
                            }

                            $date = date(
                                'Y-m-d',
                                strtotime((string) $date)
                            );

                            return $query->whereHas(
                                'consultation',
                                function (
                                    Builder $consultationQuery
                                ) use ($date): void {

                                    $consultationQuery->whereDate(
                                        'consultation_date',
                                        $date
                                    );
                                }
                            );
                        }
                    )
                    ->indicateUsing(
                        function (
                            array $data
                        ): ?string {

                            if (
                                empty(
                                    $data['date']
                                )
                            ) {
                                return null;
                            }

                            return 'Date: '.
                                date(
                                    'Y-m-d',
                                    strtotime(
                                        (string) $data['date']
                                    )
                                );
                        }
                    ),
                Filter::make('doctor')
                    ->label('Doctor')
                    ->form([

                        Select::make('doctor_id')
                            ->label('Doctor')
                            ->placeholder('All Doctors')
                            ->options(
                                fn (): array => Doctor::query()
                                    ->where(
                                        'is_active',
                                        true
                                    )
                                    ->orderBy('name')
                                    ->pluck(
                                        'name',
                                        'id'
                                    )
                                    ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->native(false),
                    ])
                    ->query(
                        function (
                            Builder $query,
                            array $data
                        ): Builder {

                            $doctorId =
                                $data['doctor_id']
                                ?? null;

                            if (! $doctorId) {
                                return $query;
                            }

                            return $query->whereHas(
                                'consultation',
                                function (
                                    Builder $consultationQuery
                                ) use ($doctorId): void {

                                    $consultationQuery->where(
                                        'doctor_id',
                                        $doctorId
                                    );
                                }
                            );
                        }
                    )
                    ->indicateUsing(
                        function (
                            array $data
                        ): ?string {

                            $doctorId =
                                $data['doctor_id']
                                ?? null;

                            if (! $doctorId) {
                                return null;
                            }

                            $doctor =
                                Doctor::find(
                                    $doctorId
                                );

                            return $doctor
                                ? 'Doctor: '.
                                    $doctor->name
                                : null;
                        }
                    ),

                SelectFilter::make(
                    'payment_status'
                )
                    ->label('Payment Status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                        'partial' => 'Partial',
                        'cancelled' => 'Cancelled',
                    ])
                    ->native(false),
            ])

            /*
            |--------------------------------------------------------------------------
            | Filter Layout
            |--------------------------------------------------------------------------
            */

            ->filtersLayout(
                FiltersLayout::AboveContent
            )

            ->filtersFormColumns(3)

            /*
            |--------------------------------------------------------------------------
            | Record Actions
            |--------------------------------------------------------------------------
            */

            ->recordActions([

                ViewAction::make(),

                /*
                |--------------------------------------------------------------------------
                | Receive Payment
                |--------------------------------------------------------------------------
                */

                Action::make(
                    'receivePayment'
                )
                    ->label('Receive Payment')
                    ->icon(
                        'heroicon-o-banknotes'
                    )
                    ->color('success')

                    ->visible(
                        fn (
                            ConsultationBill $record
                        ): bool => $record->payment_status === 'unpaid'
                            && (float) $record->balance_due > 0
                    )

                    ->form([

                        Select::make(
                            'payment_method'
                        )
                            ->label(
                                'Payment Method'
                            )
                            ->options([
                                'cash' => 'Cash',

                                'card' => 'Card',

                                'bank_transfer' => 'Bank Transfer',

                                'online' => 'Online',
                            ])
                            ->required()
                            ->native(false)
                            ->default(
                                'cash'
                            ),

                        TextInput::make(
                            'amount_received'
                        )
                            ->label(
                                'Amount Received'
                            )
                            ->numeric()
                            ->required()
                            ->step(0.01)
                            ->default(
                                fn (
                                    ConsultationBill $record
                                ): float => (float) $record->balance_due
                            )
                            ->minValue(
                                fn (
                                    ConsultationBill $record
                                ): float => (float) $record->balance_due
                            )
                            ->live(
                                debounce: 300
                            )
                            ->prefix(
                                fn (
                                    ConsultationBill $record
                                ): string => $record->currency
                            ),

                        Placeholder::make(
                            'change_amount'
                        )
                            ->label('Change')
                            ->content(
                                function (
                                    Get $get,
                                    ConsultationBill $record
                                ): string {

                                    $received =
                                        (float) (
                                            $get(
                                                'amount_received'
                                            )
                                            ?? 0
                                        );

                                    $balance =
                                        (float) $record->balance_due;

                                    $change =
                                        max(
                                            0,
                                            $received
                                            - $balance
                                        );

                                    return
                                        $record->currency.
                                        ' '.
                                        number_format(
                                            $change,
                                            2
                                        );
                                }
                            ),

                        TextInput::make(
                            'payment_reference'
                        )
                            ->label(
                                'Payment Reference'
                            )
                            ->placeholder(
                                'Optional'
                            )
                            ->maxLength(
                                255
                            ),
                    ])

                    ->modalHeading(
                        'Receive Consultation Payment'
                    )

                    ->modalDescription(
                        fn (
                            ConsultationBill $record
                        ): string => 'Bill: '.
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
                            ConsultationBill $record,
                            array $data
                        ): void {

                            $amountReceived =
                                (float) (
                                    $data[
                                        'amount_received'
                                    ]
                                    ?? 0
                                );

                            $balanceDue =
                                (float) $record->balance_due;

                            if (
                                $amountReceived
                                < $balanceDue
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
                                $data[
                                    'payment_method'
                                ],
                                $amountReceived,
                                $data[
                                    'payment_reference'
                                ] ?? null
                            );

                            $fresh =
                                $record->fresh();

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
                | Print Receipt
                |--------------------------------------------------------------------------
                */

                Action::make(
                    'printReceipt'
                )
                    ->label('Print Receipt')
                    ->icon(
                        'heroicon-o-printer'
                    )
                    ->color('info')
                    ->visible(
                        fn (
                            ConsultationBill $record
                        ): bool => $record->payment_status === 'paid'
                    )
                    ->url(
                        fn (
                            ConsultationBill $record
                        ): string => route(
                            'consultation-bills.receipt',
                            $record
                        )
                    )
                    ->openUrlInNewTab(),

                /*
                |--------------------------------------------------------------------------
                | Edit
                |--------------------------------------------------------------------------
                */

                EditAction::make()
                    ->visible(
                        fn (
                            ConsultationBill $record
                        ): bool => $record->payment_status !== 'paid'
                    ),
            ])

            /*
            |--------------------------------------------------------------------------
            | Toolbar Actions
            |--------------------------------------------------------------------------
            */

            ->toolbarActions([

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            /*
            |--------------------------------------------------------------------------
            | Default Sort
            |--------------------------------------------------------------------------
            */

            ->defaultSort(
                'created_at',
                'desc'
            );
    }
}
