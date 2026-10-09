<?php

namespace App\Filament\Resources\OnlineAppointments\Tables;

use App\Filament\Resources\Consultations\ConsultationResource;
use App\Models\Doctor;
use App\Models\OnlineAppointment;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Component;
use Throwable;

class OnlineAppointmentsTable
{
    /*
    |--------------------------------------------------------------------------
    | GET LOGGED-IN DOCTOR
    |--------------------------------------------------------------------------
    |
    | users.email = doctors.email
    |
    */

    protected static function getLoggedInDoctor(): ?Doctor
    {
        return auth()->user()?->getActiveDoctor();
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK DOCTOR LOGIN
    |--------------------------------------------------------------------------
    */

    protected static function isDoctor(): bool
    {
        return static::getLoggedInDoctor() !== null;
    }

    /*
    |--------------------------------------------------------------------------
    | CONFIGURE TABLE
    |--------------------------------------------------------------------------
    */

    public static function configure(Table $table): Table
    {
        return $table
            ->poll('10s')

            /*
            |--------------------------------------------------------------------------
            | CUSTOM HEADER
            |--------------------------------------------------------------------------
            |
            | Date picker + appointment statistics.
            |
            */

            ->header(
                function (
                    Component $livewire
                ) {

                    return view(
                        'filament.resources.online-appointments.table-header',
                        [
                            'livewire' => $livewire,
                        ]
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | COLUMNS
            |--------------------------------------------------------------------------
            */

            ->columns([

                /*
                |--------------------------------------------------------------------------
                | TOKEN
                |--------------------------------------------------------------------------
                */

                TextColumn::make('token_number')
                    ->label('Token')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(
                        fn ($state): string => filled($state)
                                ? str_pad(
                                    (string) $state,
                                    2,
                                    '0',
                                    STR_PAD_LEFT
                                )
                                : '—'
                    )
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | PATIENT
                |--------------------------------------------------------------------------
                */

                TextColumn::make('full_name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | DOCTOR
                |--------------------------------------------------------------------------
                */

                TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->placeholder('Not Assigned')
                    ->searchable()
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | ROOM
                |--------------------------------------------------------------------------
                */

                TextColumn::make('doctor.room_number')
                    ->label('Room No')
                    ->badge()
                    ->placeholder('Not Assigned')
                    ->sortable()
                    ->searchable(),

                /*
                |--------------------------------------------------------------------------
                | APPOINTMENT
                |--------------------------------------------------------------------------
                */

                TextColumn::make('appointment_date')
                    ->label('Appointment')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | SOURCE
                |--------------------------------------------------------------------------
                */

                TextColumn::make('source')
                    ->label('Source')
                    ->badge()
                    ->color(
                        fn (?string $state): string => match ($state) {

                            'Online' => 'success',

                            'Website' => 'success',

                            'Reception' => 'info',

                            default => 'gray',
                        }
                    )
                    ->sortable()
                    ->searchable(),

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string => match ($state) {

                            'confirmed' => 'Confirmed',

                            'cancelled' => 'Cancelled',

                            'rejected' => 'Rejected',

                            'pending' => 'Pending',

                            default => ucfirst(
                                (string) $state
                            ),
                        }
                    )
                    ->color(
                        fn (?string $state): string => match ($state) {

                            'confirmed' => 'success',

                            'cancelled',
                            'rejected' => 'danger',

                            'pending' => 'warning',

                            default => 'gray',
                        }
                    )
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | PAYMENT
                |--------------------------------------------------------------------------
                */

                TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string => $state === 'paid'
                                ? 'Paid'
                                : 'Unpaid'
                    )
                    ->color(
                        fn (?string $state): string => $state === 'paid'
                                ? 'success'
                                : 'warning'
                    )
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | FEE
                |--------------------------------------------------------------------------
                */

                TextColumn::make('appointment_fee')
                    ->label('Doctor Fee')
                    ->formatStateUsing(
                        fn (
                            $state,
                            OnlineAppointment $record
                        ): string => (
                            $record->invoice_currency
                            ?: 'LKR'
                        )
                            .' '
                            .number_format(
                                (float) ($state ?? 0),
                                2
                            )
                    )
                    ->sortable(),

                TextColumn::make('facility_service_fee')
                    ->label('Facilities / Service Fee')
                    ->formatStateUsing(
                        fn (
                            $state,
                            OnlineAppointment $record
                        ): string => (
                            $record->invoice_currency
                            ?: 'LKR'
                        )
                            .' '
                            .number_format(
                                (float) ($state ?? 0),
                                2
                            )
                    )
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | INVOICE
                |--------------------------------------------------------------------------
                */

                TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->placeholder('Not Created')
                    ->searchable(),

                /*
                |--------------------------------------------------------------------------
                | CONSULTATION
                |--------------------------------------------------------------------------
                */

                TextColumn::make('consultation.id')
                    ->label('Consultation')
                    ->formatStateUsing(
                        fn ($state): string => $state
                                ? 'Created'
                                : 'Not Created'
                    )
                    ->badge()
                    ->color(
                        fn ($state): string => $state
                                ? 'success'
                                : 'gray'
                    ),

            ])

            /*
            |--------------------------------------------------------------------------
            | ACTIONS
            |--------------------------------------------------------------------------
            */

            ->actions([

                ActionGroup::make([

                    Action::make('viewWebsiteRequest')
                        ->label('View Request')
                        ->icon('heroicon-o-eye')
                        ->visible(fn (OnlineAppointment $record): bool => $record->source === 'Website')
                        ->fillForm(fn (OnlineAppointment $record): array => [
                            'booking_number' => $record->booking_number,
                            'full_name' => $record->full_name,
                            'phone_number' => $record->phone_number,
                            'notes' => $record->notes,
                        ])
                        ->form([
                            TextInput::make('booking_number')->label('Booking Number')->disabled(),
                            TextInput::make('full_name')->label('Patient')->disabled(),
                            TextInput::make('phone_number')->label('Phone / WhatsApp')->disabled(),
                            Textarea::make('notes')->label('Request Details')->rows(7)->disabled(),
                        ])
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Close'),

                    /*
                    |--------------------------------------------------------------------------
                    | CONFIRM APPOINTMENT
                    |--------------------------------------------------------------------------
                    */

                    Action::make('confirm')
                        ->label('Confirm Appointment')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(
                            fn (
                                OnlineAppointment $record
                            ): bool => ! static::isDoctor()
                                &&
                                $record->status === 'pending'
                        )
                        ->fillForm(
                            fn (
                                OnlineAppointment $record
                            ): array => [
                                'doctor_id' => $record->doctor_id,

                                'facility_service_fee' => (float) $record->facility_service_fee,
                            ]
                        )
                        ->form([
                            Select::make('doctor_id')
                                ->label('Doctor')
                                ->options(
                                    fn (): array => Doctor::query()
                                        ->where('is_active', true)
                                        ->orderBy('name')
                                        ->pluck('name', 'id')
                                        ->all()
                                )
                                ->searchable()
                                ->required()
                                ->helperText(
                                    'Website requests can be saved before a doctor is assigned. Select the doctor before confirmation.'
                                ),

                            TextInput::make('facility_service_fee')
                                ->label('Facilities / Service Fee')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(9999999999.99)
                                ->required()
                                ->helperText(
                                    'This fee is separate from the doctor consultation fee and will appear as its own invoice line.'
                                ),
                        ])
                        ->action(
                            function (
                                OnlineAppointment $record,
                                array $data
                            ): void {

                                try {
                                    $doctor = Doctor::query()
                                        ->whereKey($data['doctor_id'])
                                        ->where('is_active', true)
                                        ->first();

                                    if (! $doctor) {
                                        Notification::make()
                                            ->title(
                                                'Please select an active doctor.'
                                            )
                                            ->danger()
                                            ->send();

                                        return;
                                    }

                                    $record->update([
                                        'doctor_id' => $doctor->id,

                                        'facility_service_fee' => round(
                                            (float) $data['facility_service_fee'],
                                            2
                                        ),

                                        'status' => 'confirmed',

                                        'reviewed_by_user_id' => auth()->id(),

                                        'reviewed_at' => now(),
                                    ]);

                                    $record->ensureInvoice();

                                    Notification::make()
                                        ->title(
                                            'Appointment confirmed'
                                        )
                                        ->body(
                                            'Invoice created: '
                                            .$record->invoice_number
                                        )
                                        ->success()
                                        ->send();

                                } catch (Throwable $e) {

                                    Notification::make()
                                        ->title(
                                            'Error: '
                                            .$e->getMessage()
                                        )
                                        ->danger()
                                        ->persistent()
                                        ->send();
                                }
                            }
                        ),

                    /*
                    |--------------------------------------------------------------------------
                    | CANCEL / NO SHOW
                    |--------------------------------------------------------------------------
                    */

                    Action::make('cancel')
                        ->label('Cancel / No Show')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(
                            fn (
                                OnlineAppointment $record
                            ): bool => ! static::isDoctor()
                                &&
                                in_array(
                                    $record->status,
                                    [
                                        'pending',
                                        'confirmed',
                                    ],
                                    true
                                )
                                &&
                                ! filled(
                                    $record->consultation_id
                                )
                        )
                        ->form([

                            Select::make('cancellation_reason')
                                ->label('Reason')
                                ->options([

                                    'Patient did not attend' => 'Patient did not attend',

                                    'Patient requested cancellation' => 'Patient requested cancellation',

                                    'Doctor unavailable' => 'Doctor unavailable',

                                    'Other' => 'Other',
                                ])
                                ->required()
                                ->default(
                                    'Patient did not attend'
                                ),

                            Textarea::make('cancellation_note')
                                ->label('Additional Note')
                                ->placeholder(
                                    'Optional additional information...'
                                )
                                ->rows(3)
                                ->maxLength(1000),
                        ])
                        ->requiresConfirmation()
                        ->modalHeading('Cancel Appointment')
                        ->modalDescription(
                            'This appointment will be marked as cancelled.'
                        )
                        ->action(
                            function (
                                OnlineAppointment $record,
                                array $data
                            ): void {

                                try {

                                    $reason =
                                        $data['cancellation_reason'];

                                    $note =
                                        trim(
                                            (string) (
                                                $data['cancellation_note']
                                                ?? ''
                                            )
                                        );

                                    $rejectionReason = $reason;

                                    if ($note !== '') {

                                        $rejectionReason .=
                                            ' — '.$note;
                                    }

                                    $record->update([
                                        'status' => 'cancelled',

                                        'rejection_reason' => $rejectionReason,

                                        'reviewed_by_user_id' => auth()->id(),

                                        'reviewed_at' => now(),
                                    ]);

                                    Notification::make()
                                        ->title(
                                            'Appointment cancelled'
                                        )
                                        ->body(
                                            'Token No: '
                                            .str_pad(
                                                (string) (
                                                    $record->token_number
                                                ),
                                                2,
                                                '0',
                                                STR_PAD_LEFT
                                            )
                                            .' | '
                                            .$reason
                                        )
                                        ->success()
                                        ->send();

                                } catch (Throwable $e) {

                                    Notification::make()
                                        ->title(
                                            'Cancellation error'
                                        )
                                        ->body(
                                            $e->getMessage()
                                        )
                                        ->danger()
                                        ->persistent()
                                        ->send();
                                }
                            }
                        ),

                    /*
                    |--------------------------------------------------------------------------
                    | PRINT BILL
                    |--------------------------------------------------------------------------
                    */

                    Action::make('printBill')
                        ->label('Print Bill')
                        ->icon('heroicon-o-printer')
                        ->color('info')
                        ->visible(
                            fn (
                                OnlineAppointment $record
                            ): bool => ! static::isDoctor()
                                &&
                                filled(
                                    $record->invoice_number
                                )
                        )
                        ->url(
                            fn (
                                OnlineAppointment $record
                            ): string => route(
                                'billing.appointment.print',
                                [
                                    'appointment' => $record->id,
                                ]
                            )
                        )
                        ->openUrlInNewTab(),

                    /*
                    |--------------------------------------------------------------------------
                    | INVOICE
                    |--------------------------------------------------------------------------
                    */

                    Action::make('invoice')
                        ->label('View / Download Invoice')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->visible(
                            fn (
                                OnlineAppointment $record
                            ): bool => ! static::isDoctor()
                                &&
                                filled(
                                    $record->invoice_number
                                )
                        )
                        ->url(
                            fn (
                                OnlineAppointment $record
                            ): string => route(
                                'billing.appointment.invoice',
                                [
                                    'appointment' => $record->id,
                                ]
                            )
                        )
                        ->openUrlInNewTab(),

                    /*
                    |--------------------------------------------------------------------------
                    | RECORD PAYMENT
                    |--------------------------------------------------------------------------
                    */

                    Action::make('recordPayment')
                        ->label('Record Payment')
                        ->icon('heroicon-o-banknotes')
                        ->color('success')
                        ->visible(
                            fn (
                                OnlineAppointment $record
                            ): bool => ! static::isDoctor()
                                &&
                                $record->status === 'confirmed'
                                &&
                                $record->payment_status !== 'paid'
                        )
                        ->form([

                            Select::make('payment_method')
                                ->label('Payment Method')
                                ->options([

                                    'Cash' => 'Cash',

                                    'Card' => 'Card',

                                    'Bank Transfer' => 'Bank Transfer',

                                    'Online' => 'Online',

                                    'Other' => 'Other',
                                ])
                                ->required()
                                ->default('Cash'),

                            TextInput::make('payment_reference')
                                ->label('Payment Reference')
                                ->placeholder(
                                    'Optional receipt / transaction reference'
                                )
                                ->maxLength(255),
                        ])
                        ->requiresConfirmation()
                        ->modalHeading(
                            'Record Appointment Payment'
                        )
                        ->modalDescription(
                            'Confirm that the patient has paid the appointment fee.'
                        )
                        ->action(
                            function (
                                OnlineAppointment $record,
                                array $data
                            ): void {

                                try {

                                    $record->markAsPaid(
                                        $data['payment_method'],
                                        $data['payment_reference']
                                            ?? null
                                    );

                                    Notification::make()
                                        ->title(
                                            'Payment recorded successfully'
                                        )
                                        ->body(
                                            'Receipt: '
                                            .$record->receipt_number
                                        )
                                        ->success()
                                        ->send();

                                } catch (Throwable $e) {

                                    Notification::make()
                                        ->title(
                                            'Payment error: '
                                            .$e->getMessage()
                                        )
                                        ->danger()
                                        ->persistent()
                                        ->send();
                                }
                            }
                        ),

                    /*
                    |--------------------------------------------------------------------------
                    | PRINT RECEIPT
                    |--------------------------------------------------------------------------
                    */

                    Action::make('printReceipt')
                        ->label('Print Receipt')
                        ->icon('heroicon-o-printer')
                        ->color('success')
                        ->visible(
                            fn (
                                OnlineAppointment $record
                            ): bool => ! static::isDoctor()
                                &&
                                $record->payment_status === 'paid'
                                &&
                                filled(
                                    $record->receipt_number
                                )
                        )
                        ->url(
                            fn (
                                OnlineAppointment $record
                            ): string => route(
                                'billing.appointment.receipt.print',
                                [
                                    'appointment' => $record->id,
                                ]
                            )
                        )
                        ->openUrlInNewTab(),

                    /*
                    |--------------------------------------------------------------------------
                    | RECEIPT
                    |--------------------------------------------------------------------------
                    */

                    Action::make('receipt')
                        ->label('View / Download Receipt')
                        ->icon('heroicon-o-receipt-percent')
                        ->color('success')
                        ->visible(
                            fn (
                                OnlineAppointment $record
                            ): bool => ! static::isDoctor()
                                &&
                                $record->payment_status === 'paid'
                                &&
                                filled(
                                    $record->receipt_number
                                )
                        )
                        ->url(
                            fn (
                                OnlineAppointment $record
                            ): string => route(
                                'billing.appointment.receipt',
                                [
                                    'appointment' => $record->id,
                                ]
                            )
                        )
                        ->openUrlInNewTab(),

                    /*
                    |--------------------------------------------------------------------------
                    | CREATE CONSULTATION
                    |--------------------------------------------------------------------------
                    */

                    Action::make('createConsultation')
                        ->label('Create Consultation')
                        ->icon(
                            'heroicon-o-clipboard-document-list'
                        )
                        ->color('primary')
                        ->visible(
                            fn (
                                OnlineAppointment $record
                            ): bool => $record->status === 'confirmed'
                                &&
                                $record->payment_status === 'paid'
                                &&
                                ! $record->consultation_id
                        )
                        ->url(
                            fn (
                                OnlineAppointment $record
                            ): string => ConsultationResource::getUrl(
                                'create',
                                [
                                    'appointment' => $record->id,
                                ]
                            )
                        ),

                    /*
                    |--------------------------------------------------------------------------
                    | VIEW CONSULTATION
                    |--------------------------------------------------------------------------
                    */

                    Action::make('viewConsultation')
                        ->label('View Consultation')
                        ->icon('heroicon-o-eye')
                        ->color('gray')
                        ->visible(
                            fn (
                                OnlineAppointment $record
                            ): bool => filled(
                                $record->consultation_id
                            )
                        )
                        ->url(
                            fn (
                                OnlineAppointment $record
                            ): string => ConsultationResource::getUrl(
                                'view',
                                [
                                    'record' => $record->consultation_id,
                                ]
                            )
                        ),

                    /*
                    |--------------------------------------------------------------------------
                    | REJECT
                    |--------------------------------------------------------------------------
                    */

                    Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(
                            fn (
                                OnlineAppointment $record
                            ): bool => ! static::isDoctor()
                                &&
                                $record->status === 'pending'
                        )
                        ->form([

                            Textarea::make('rejection_reason')
                                ->label('Reason')
                                ->required()
                                ->maxLength(1000),
                        ])
                        ->action(
                            function (
                                OnlineAppointment $record,
                                array $data
                            ): void {

                                try {

                                    $record->update([
                                        'status' => 'rejected',

                                        'rejection_reason' => $data['rejection_reason'],

                                        'reviewed_by_user_id' => auth()->id(),

                                        'reviewed_at' => now(),
                                    ]);

                                    Notification::make()
                                        ->title(
                                            'Appointment rejected'
                                        )
                                        ->success()
                                        ->send();

                                } catch (Throwable $e) {

                                    Notification::make()
                                        ->title(
                                            'Error: '
                                            .$e->getMessage()
                                        )
                                        ->danger()
                                        ->persistent()
                                        ->send();
                                }
                            }
                        ),

                ]),
            ])

            /*
            |--------------------------------------------------------------------------
            | DEFAULT SORT
            |--------------------------------------------------------------------------
            */

            ->defaultSort(
                'appointment_date',
                'asc'
            );
    }
}
