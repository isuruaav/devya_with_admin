<?php

namespace App\Filament\Resources\Patients\Schemas;

use App\Models\Country;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->extraAttributes(['class' => 'admin-form patient-form'])
            ->components([

                Section::make('Patient Identification')
                    ->contained(false)
                    ->schema([

                        TextInput::make('nic_or_passport')
                            ->label('NIC / Passport No')
                            ->placeholder('Enter NIC or Passport Number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),

                        TextInput::make('full_name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),

                        Select::make('patient_type')
                            ->label('Patient Type')
                            ->options([
                                'Local' => '🇱🇰 Local Patient',
                                'Foreign' => '✈️ Foreign Patient',
                            ])
                            ->default('Local')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {

                                if ($state === 'Local') {

                                    $sriLanka = Country::where(
                                        'name',
                                        'Sri Lanka'
                                    )->first();

                                    if ($sriLanka) {
                                        $set('country_id', $sriLanka->id);
                                    }

                                } else {
                                    $set('country_id', null);
                                }
                            }),

                        Select::make('country_id')
                            ->label('Country')
                            ->relationship('country', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                Section::make('Contact Information')
                    ->contained(false)
                    ->schema([

                        TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->tel()
                            ->required(),

                        TextInput::make('whatsapp_number')
                            ->label('WhatsApp Number')
                            ->tel()
                            ->placeholder('94771234567'),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->placeholder('patient@example.com')
                            ->columnSpanFull(),

                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                Section::make('Personal Details')
                    ->contained(false)
                    ->schema([

                        Grid::make(['default' => 1, 'md' => 3])
                            ->schema([

                                DatePicker::make('date_of_birth')
                                    ->label('Date of Birth')
                                    ->native(false)
                                    ->displayFormat('Y-m-d')
                                    ->maxDate(now())
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {

                                        if ($state) {
                                            $age = Carbon::parse($state)->age;
                                            $set('age', $age);
                                        } else {
                                            $set('age', null);
                                        }

                                    }),

                                TextInput::make('age')
                                    ->label('Age')
                                    ->numeric()
                                    ->readOnly()
                                    ->dehydrated(true),

                                Select::make('gender')
                                    ->label('Gender')
                                    ->options([
                                        'Male' => 'Male',
                                        'Female' => 'Female',
                                        'Other' => 'Other',
                                    ])
                                    ->default('Male')
                                    ->required(),

                            ]),

                        Textarea::make('address')
                            ->label('Address / Hotel Name')
                            ->columnSpanFull(),

                    ]),

                Section::make('Medical Information')
                    ->contained(false)
                    ->schema([

                        Textarea::make('allergies')
                            ->label('Allergies')
                            ->rows(4),

                        Textarea::make('medical_history')
                            ->label('Medical History')
                            ->rows(4),

                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                Section::make('Documents & Photos')
                    ->contained(false)
                    ->schema([

                        FileUpload::make('patient_photo')
                            ->label('Patient Photo')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('patients/photos')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->nullable(),

                        FileUpload::make('nic_passport_copy')
                            ->label('NIC / Passport Copy')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->disk('confidential')
                            ->directory('patients/documents')
                            ->visibility('private')
                            ->maxSize(5120)
                            ->downloadable()
                            ->openable()
                            ->nullable(),

                    ])
                    ->columns(['default' => 1, 'md' => 2]),

            ]);
    }
}
