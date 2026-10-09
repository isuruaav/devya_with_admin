<?php

namespace App\Filament\Resources\Staff\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StaffInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | Staff Profile
                |--------------------------------------------------------------------------
                */
                Section::make('Staff Profile')
                    ->schema([
                        ImageEntry::make('photo')
                            ->label('Photo')
                            ->disk('public')
                            ->circular(),

                        TextEntry::make('staff_code')
                            ->label('Staff ID'),

                        TextEntry::make('full_name')
                            ->label('Full Name'),

                        TextEntry::make('name_with_initials')
                            ->label('Name with Initials'),

                        TextEntry::make('nic_passport')
                            ->label('NIC / Passport'),

                        TextEntry::make('date_of_birth')
                            ->label('Date of Birth')
                            ->date('d/m/Y'),

                        TextEntry::make('gender')
                            ->label('Gender'),
                    ])
                    ->columns(3),

                /*
                |--------------------------------------------------------------------------
                | QR Attendance
                |--------------------------------------------------------------------------
                */
                Section::make('Attendance QR Code')
                    ->description(
                        'Scan this QR code to automatically record staff attendance.'
                    )
                    ->schema([

                        ImageEntry::make('attendance_qr')
                            ->label('Staff QR Code')
                            ->state(function ($record): string {

                                if (empty($record->qr_token)) {
                                    return '';
                                }

                                $svg = QrCode::format('svg')
                                    ->size(250)
                                    ->margin(2)
                                    ->generate($record->qr_token);

                                return 'data:image/svg+xml;base64,'.
                                    base64_encode($svg);
                            })
                            ->height(250)
                            ->width(250),

                        TextEntry::make('qr_status')
                            ->label('QR Status')
                            ->state(function ($record): string {
                                return empty($record->qr_token)
                                    ? 'QR Token Not Generated'
                                    : 'QR Active';
                            })
                            ->badge()
                            ->color(function ($state): string {
                                return $state === 'QR Active'
                                    ? 'success'
                                    : 'danger';
                            }),

                    ])
                    ->columns(2),

                /*
                |--------------------------------------------------------------------------
                | Contact Information
                |--------------------------------------------------------------------------
                */
                Section::make('Contact Information')
                    ->schema([
                        TextEntry::make('phone')
                            ->label('Phone'),

                        TextEntry::make('email')
                            ->label('Email'),

                        TextEntry::make('address')
                            ->label('Address')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                /*
                |--------------------------------------------------------------------------
                | Emergency Contact
                |--------------------------------------------------------------------------
                */
                Section::make('Emergency Contact')
                    ->schema([
                        TextEntry::make('emergency_contact_name')
                            ->label('Contact Name'),

                        TextEntry::make('emergency_contact_phone')
                            ->label('Contact Phone'),

                        TextEntry::make('emergency_contact_relationship')
                            ->label('Relationship'),
                    ])
                    ->columns(3),

                /*
                |--------------------------------------------------------------------------
                | Employment Details
                |--------------------------------------------------------------------------
                */
                Section::make('Employment Details')
                    ->schema([
                        TextEntry::make('designation')
                            ->label('Designation'),

                        TextEntry::make('department')
                            ->label('Department'),

                        TextEntry::make('joining_date')
                            ->label('Joining Date')
                            ->date('d/m/Y'),

                        TextEntry::make('employment_type')
                            ->label('Employment Type'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge(),
                    ])
                    ->columns(2),

                /*
                |--------------------------------------------------------------------------
                | Salary Details
                |--------------------------------------------------------------------------
                */
                Section::make('Salary Details')
                    ->schema([
                        TextEntry::make('basic_salary')
                            ->label('Basic Salary')
                            ->money('LKR'),

                        TextEntry::make('allowance')
                            ->label('Allowance')
                            ->money('LKR'),

                        TextEntry::make('total_salary')
                            ->label('Current Total Salary')
                            ->state(fn ($record) => $record->basic_salary + $record->allowance
                            )
                            ->money('LKR'),
                    ])
                    ->columns(3),

                /*
                |--------------------------------------------------------------------------
                | Record Information
                |--------------------------------------------------------------------------
                */
                Section::make('Record Information')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d/m/Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),

                /*
                |--------------------------------------------------------------------------
                | Additional Information
                |--------------------------------------------------------------------------
                */
                Section::make('Additional Information')
                    ->schema([
                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('No additional notes.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
