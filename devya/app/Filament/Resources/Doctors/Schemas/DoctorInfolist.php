<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class DoctorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | Doctor Profile
                |--------------------------------------------------------------------------
                */
                Section::make('Doctor Profile')
                    ->schema([
                        ImageEntry::make('doctor_photo')
                            ->label('Photo')
                            ->disk('public')
                            ->circular(),

                        TextEntry::make('name')
                            ->label('Doctor Name'),

                        TextEntry::make('nic_number')
                            ->label('NIC Number'),

                        TextEntry::make('reg_no')
                            ->label('Reg No'),

                        TextEntry::make('specialization')
                            ->label('Specialization'),

                        TextEntry::make('qualification')
                            ->label('Qualification'),

                        TextEntry::make('phone_number')
                            ->label('Phone Number'),

                        TextEntry::make('whatsapp_number')
                            ->label('WhatsApp Number'),

                        TextEntry::make('email')
                            ->label('Email Address'),

                        TextEntry::make('room_number')
                            ->label('Consulting Room'),

                        TextEntry::make('local_fee')
                            ->label('Local Fee')
                            ->money('LKR'),

                        TextEntry::make('foreign_fee')
                            ->label('Foreign Fee ($)')
                            ->money('USD'),

                        TextEntry::make('max_patients_per_day')
                            ->label('Max Patients/Day'),

                        TextEntry::make('availability_status')
                            ->label('Availability Status')
                            ->badge()
                            ->formatStateUsing(
                                fn (?string $state): string => match ($state) {
                                    'available' => 'Available',
                                    'on_leave' => 'On Leave',
                                    'temporarily_unavailable' => 'Temporarily Unavailable',
                                    default => str_replace(
                                        '_',
                                        ' ',
                                        ucfirst((string) $state)
                                    ),
                                }
                            )
                            ->color(
                                fn (?string $state): string => match ($state) {
                                    'available' => 'success',
                                    'on_leave' => 'warning',
                                    'temporarily_unavailable' => 'danger',
                                    default => 'gray',
                                }
                            ),

                        TextEntry::make('available_days')
                            ->label('Available Days')
                            ->formatStateUsing(
                                fn ($state): string => collect($state ?? [])
                                    ->map(
                                        fn (string $day): string => ucfirst($day)
                                    )
                                    ->join(', ')
                                ?: 'Not Set'
                            ),

                        TextEntry::make('schedule_start')
                            ->label('Available From'),

                        TextEntry::make('schedule_end')
                            ->label('Available To'),

                        TextEntry::make('slmc_document_expiry')
                            ->label('SLMC Expiry')
                            ->date('d M Y'),

                        TextEntry::make('nic_document_expiry')
                            ->label('NIC / Passport Expiry')
                            ->date('d M Y'),

                        TextEntry::make('consultations_count')
                            ->label('Total Consultations')
                            ->state(
                                fn ($record): int => $record->consultations()->count()
                            ),

                        TextEntry::make('today_consultations_count')
                            ->label('Booked Today')
                            ->state(
                                fn ($record): string => sprintf(
                                    '%d / %d',
                                    $record->today_consultations_count,
                                    $record->max_patients_per_day ?? 0,
                                )
                            ),

                        TextEntry::make('today_remaining')
                            ->label('Remaining Today')
                            ->state(
                                fn ($record): int => max(
                                    0,
                                    (int) ($record->max_patients_per_day ?? 0)
                                    - (int) $record->today_consultations_count,
                                )
                            ),

                        TextEntry::make('month_doctor_fees')
                            ->label('Monthly Doctor Fees')
                            ->money('LKR'),

                        TextEntry::make('month_income')
                            ->label('Monthly Income')
                            ->state(
                                fn ($record): string => sprintf(
                                    'LKR %s | USD %s',
                                    number_format(
                                        (float) $record->consultations()
                                            ->where('status', '!=', 'cancelled')
                                            ->whereBetween(
                                                'consultation_date',
                                                [
                                                    now()->startOfMonth(),
                                                    now()->endOfMonth(),
                                                ]
                                            )
                                            ->where('currency', 'LKR')
                                            ->sum('grand_total'),
                                        2
                                    ),
                                    number_format(
                                        (float) $record->consultations()
                                            ->where('status', '!=', 'cancelled')
                                            ->whereBetween(
                                                'consultation_date',
                                                [
                                                    now()->startOfMonth(),
                                                    now()->endOfMonth(),
                                                ]
                                            )
                                            ->where('currency', 'USD')
                                            ->sum('grand_total'),
                                        2
                                    ),
                                )
                            ),

                        TextEntry::make('nic_copy')
                            ->label('NIC / Passport Document')
                            ->url(
                                fn ($record) => $record->nic_copy
                                        ? Storage::disk('confidential')->temporaryUrl($record->nic_copy, now()->addMinutes(30))
                                        : null,
                                true
                            ),

                        TextEntry::make('slmc_registration_document')
                            ->label('SLMC Registration Document')
                            ->url(
                                fn ($record) => $record->slmc_registration_document
                                        ? Storage::disk('confidential')->temporaryUrl($record->slmc_registration_document, now()->addMinutes(30))
                                        : null,
                                true
                            ),

                        IconEntry::make('is_active')
                            ->label('Active Status')
                            ->boolean(),
                    ])
                    ->columns(2),

                /*
                |--------------------------------------------------------------------------
                | Date-specific Schedule
                |--------------------------------------------------------------------------
                */
                Section::make('Date-specific Schedule')
                    ->description(
                        'Special dates that override the normal weekly availability.'
                    )
                    ->schema([
                        RepeatableEntry::make('schedules')
                            ->label('')
                            ->schema([
                                TextEntry::make('schedule_date')
                                    ->label('Date')
                                    ->date('d M Y'),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->formatStateUsing(
                                        fn (?string $state): string => match ($state) {
                                            'available' => 'Available',
                                            'on_leave' => 'On Leave',
                                            'temporarily_unavailable' => 'Temporarily Unavailable',
                                            default => str_replace(
                                                '_',
                                                ' ',
                                                ucfirst((string) $state)
                                            ),
                                        }
                                    )
                                    ->color(
                                        fn (?string $state): string => match ($state) {
                                            'available' => 'success',
                                            'on_leave' => 'warning',
                                            'temporarily_unavailable' => 'danger',
                                            default => 'gray',
                                        }
                                    ),

                                TextEntry::make('start_time')
                                    ->label('Start Time'),

                                TextEntry::make('end_time')
                                    ->label('End Time'),

                                TextEntry::make('note')
                                    ->label('Note / Reason')
                                    ->placeholder('No note'),
                            ])
                            ->columns(5),
                    ]),
            ]);
    }
}
