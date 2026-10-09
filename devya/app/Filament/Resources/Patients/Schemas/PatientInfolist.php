<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class PatientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // =====================================================
                // PATIENT PROFILE + CONTACT
                // =====================================================
                Grid::make([
                    'default' => 1,
                    'lg' => 2,
                ])
                    ->schema([

                        // Patient Profile
                        Section::make('Patient Profile')
                            ->description('Basic patient information')
                            ->icon('heroicon-o-user-circle')
                            ->extraAttributes(['class' => 'patient-profile-section'])
                            ->schema([

                                Grid::make([
                                    'default' => 1,
                                    'sm' => 3,
                                ])
                                    ->schema([

                                        // Patient Photo
                                        ImageEntry::make('patient_photo')
                                            ->label('Photo')
                                            ->disk('public')
                                            ->height(160)
                                            ->width(130)
                                            ->extraAttributes(['class' => 'patient-profile-photo'])
                                            ->columnSpan(1),

                                        // Patient Details
                                        Grid::make([
                                            'default' => 1,
                                            'sm' => 2,
                                        ])
                                            ->schema([

                                                TextEntry::make('patient_type')
                                                    ->label('Patient Type')
                                                    ->badge()
                                                    ->color('primary'),

                                                TextEntry::make('gender')
                                                    ->label('Gender')
                                                    ->badge()
                                                    ->color('info'),

                                                TextEntry::make('full_name')
                                                    ->label('Full Name')
                                                    ->icon('heroicon-o-user')
                                                    ->weight('bold')
                                                    ->columnSpanFull(),

                                                TextEntry::make('nic_or_passport')
                                                    ->label('NIC / Passport No.')
                                                    ->icon('heroicon-o-identification')
                                                    ->placeholder('-'),

                                                TextEntry::make('date_of_birth')
                                                    ->label('Date of Birth')
                                                    ->date('d M Y')
                                                    ->icon('heroicon-o-calendar')
                                                    ->placeholder('-'),

                                                TextEntry::make('age')
                                                    ->label('Age')
                                                    ->suffix(' years')
                                                    ->icon('heroicon-o-cake')
                                                    ->placeholder('-'),

                                                TextEntry::make('country.name')
                                                    ->label('Country')
                                                    ->formatStateUsing(function ($state, $record) {
                                                        return $record->country
                                                            ? $record->country->name
                                                                .' ('.$record->country->code.')'
                                                            : '-';
                                                    })
                                                    ->badge()
                                                    ->color('success')
                                                    ->icon('heroicon-o-globe-alt'),

                                            ])
                                            ->columnSpan(2),

                                    ]),

                            ]),

                        // Contact Information
                        Section::make('Contact Information')
                            ->icon('heroicon-o-phone')
                            ->extraAttributes(['class' => 'patient-contact-section'])
                            ->schema([

                                TextEntry::make('phone_number')
                                    ->label('Phone Number')
                                    ->icon('heroicon-o-device-phone-mobile')
                                    ->copyable()
                                    ->copyMessage('Phone number copied'),

                                TextEntry::make('whatsapp_number')
                                    ->label('WhatsApp Number')
                                    ->icon('heroicon-o-chat-bubble-left-right')
                                    ->copyable()
                                    ->placeholder('-'),

                                TextEntry::make('email')
                                    ->label('Email Address')
                                    ->icon('heroicon-o-envelope')
                                    ->copyable()
                                    ->placeholder('-'),

                                TextEntry::make('address')
                                    ->label('Address / Hotel Name')
                                    ->icon('heroicon-o-home')
                                    ->placeholder('-'),

                            ]),

                    ])
                    ->columnSpanFull(),

                // =====================================================
                // MEDICAL INFORMATION
                // =====================================================
                Section::make('Medical Information')
                    ->description('Important medical information about the patient')
                    ->icon('heroicon-o-heart')
                    ->extraAttributes(['class' => 'patient-medical-section'])
                    ->schema([

                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([

                                TextEntry::make('allergies')
                                    ->label('Allergies')
                                    ->icon('heroicon-o-exclamation-triangle')
                                    ->placeholder('No allergies recorded')
                                    ->prose(),

                                TextEntry::make('medical_history')
                                    ->label('Medical History')
                                    ->icon('heroicon-o-clipboard-document-list')
                                    ->placeholder('No medical history recorded')
                                    ->prose(),

                            ]),

                    ])
                    ->columnSpanFull(),

                // =====================================================
                // DOCUMENTS + RECORD INFORMATION
                // =====================================================
                Grid::make(2)
                    ->schema([

                        // Documents
                        Section::make('Documents')
                            ->description('Patient documents')
                            ->icon('heroicon-o-document-text')
                            ->extraAttributes(['class' => 'patient-documents-section'])
                            ->schema([

                                TextEntry::make('nic_passport_copy')
                                    ->label('NIC / Passport Copy')
                                    ->formatStateUsing(fn ($state) => $state
                                        ? 'View NIC / Passport Copy'
                                        : 'No document uploaded'
                                    )
                                    ->url(fn ($state) => $state
                                        ? Storage::disk('confidential')->temporaryUrl($state, now()->addMinutes(30))
                                        : null
                                    )
                                    ->openUrlInNewTab()
                                    ->icon('heroicon-o-document-text')
                                    ->color('primary'),

                            ]),

                        // Record Information
                        Section::make('Record Information')
                            ->icon('heroicon-o-clock')
                            ->extraAttributes(['class' => 'patient-record-section'])
                            ->schema([

                                TextEntry::make('created_at')
                                    ->label('Registered Date')
                                    ->dateTime('d M Y, h:i A')
                                    ->icon('heroicon-o-calendar'),

                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->dateTime('d M Y, h:i A')
                                    ->icon('heroicon-o-arrow-path'),

                            ])
                            ->collapsed(),

                    ])
                    ->columnSpanFull(),

            ]);
    }
}
