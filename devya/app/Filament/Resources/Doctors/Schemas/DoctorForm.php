<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DoctorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->extraAttributes(['class' => 'admin-form doctor-form'])
            ->components([
                Section::make('Personal & Identification')
                    ->contained(false)
                    ->schema([
                        TextInput::make('name')
                            ->label('Doctor Name (වෛද්‍යවරයාගේ නම)')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('nic_number')
                            ->label('NIC Number (ජාතික හැඳුනුම්පත් අංකය)')
                            ->placeholder('e.g. 199012345678 or 901234567V')
                            ->unique(ignoreRecord: true),
                        TextInput::make('reg_no')
                            ->label('Ayurvedic Reg No (ලියාපදිංචි අංකය)')
                            ->placeholder('e.g. A-1234'),
                        TextInput::make('specialization')
                            ->label('Specialization (විශේෂඥතාවය)')
                            ->placeholder('e.g. Panchakarma, Nadi Pariksha'),
                        TextInput::make('qualification')
                            ->label('Qualification (සුදුසුකම්)')
                            ->placeholder('e.g. BAMS (Colombo), MD (Ayu)'),
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
                            ->placeholder('0771234567'),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->placeholder('doctor@example.com'),
                        TextInput::make('room_number')
                            ->label('Consulting Room No / OPD Room')
                            ->placeholder('Room 01'),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                Section::make('Consultation Fees & Settings')
                    ->contained(false)
                    ->schema([
                        TextInput::make('local_fee')
                            ->label('Local Patient Fee (LKR)')
                            ->numeric()
                            ->prefix('Rs.')
                            ->default(0.00)
                            ->required(),
                        TextInput::make('foreign_fee')
                            ->label('Foreign Patient Fee (USD)')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00)
                            ->required(),
                        TextInput::make('max_patients_per_day')
                            ->label('Max Patients Per Day (දිනකට උපරිම රෝගීන්)')
                            ->numeric()
                            ->default(20),
                        Toggle::make('is_active')
                            ->label('Active Status')
                            ->inline(false)
                            ->default(true),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                Section::make('Documents & Profile Photo')
                    ->contained(false)
                    ->schema([
                        FileUpload::make('doctor_photo')
                            ->label('Doctor Photo')
                            ->image()
                            ->avatar()
                            ->disk('public')
                            ->directory('doctors/photos')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                        FileUpload::make('nic_copy')
                            ->label('NIC / Passport Copy')
                            ->disk('confidential')
                            ->visibility('private')
                            ->directory('doctors/documents')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/*',
                            ])
                            ->maxSize(5120)
                            ->downloadable()
                            ->openable(),
                        FileUpload::make('slmc_registration_document')
                            ->label('SLMC Registration Document')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->disk('confidential')
                            ->directory('doctors/slmc-documents')
                            ->visibility('private')
                            ->maxSize(5120)
                            ->downloadable()
                            ->openable(),
                        DatePicker::make('nic_document_expiry')
                            ->label('NIC / Passport Expiry'),
                        DatePicker::make('slmc_document_expiry')
                            ->label('SLMC Document Expiry'),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                Section::make('Availability & Weekly Schedule')
                    ->contained(false)
                    ->schema([
                        Select::make('availability_status')
                            ->label('Availability Status')
                            ->options([
                                'available' => 'Available',
                                'on_leave' => 'On Leave',
                                'temporarily_unavailable' => 'Temporarily Unavailable',
                            ])
                            ->default('available')
                            ->required(),
                        Repeater::make('weeklySchedules')
                            ->label('Weekly Schedule')
                            ->relationship()
                            ->schema([
                                Select::make('day_of_week')
                                    ->label('Day')
                                    ->options([
                                        'monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday',
                                    ])
                                    ->required()
                                    ->disableOptionWhen(
                                        fn (string $value, $state): bool => collect($state ?? [])
                                            ->where('day_of_week', $value)
                                            ->count() > 1
                                    ),
                                Toggle::make('is_available')
                                    ->label('Available')
                                    ->inline(false)
                                    ->default(false)
                                    ->live(),
                                TimePicker::make('start_time')
                                    ->label('From')
                                    ->seconds(false)
                                    ->visible(fn ($get): bool => (bool) $get('is_available')),
                                TimePicker::make('end_time')
                                    ->label('To')
                                    ->seconds(false)
                                    ->visible(fn ($get): bool => (bool) $get('is_available')),
                            ])
                            ->columns(['default' => 1, 'md' => 2, 'xl' => 4])
                            ->defaultItems(1)
                            ->addActionLabel('Add Another Day')
                            ->reorderable(false)
                            ->deletable(true)
                            ->collapsible()
                            ->itemLabel(
                                fn (array $state): string => isset($state['day_of_week'])
                                    ? ucfirst($state['day_of_week'])
                                    : 'Weekly Schedule'
                            )
                            ->columnSpanFull(),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                Section::make('Date-specific Schedule')
                    ->contained(false)
                    ->schema([
                        Repeater::make('schedules')
                            ->hiddenLabel()
                            ->relationship()
                            ->schema([
                                DatePicker::make('schedule_date')
                                    ->label('Date')
                                    ->required()
                                    ->distinct(),
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'available' => 'Available',
                                        'on_leave' => 'On Leave',
                                        'temporarily_unavailable' => 'Temporarily Unavailable',
                                    ])
                                    ->default('available')
                                    ->required(),
                                TimePicker::make('start_time')
                                    ->label('Start Time'),
                                TimePicker::make('end_time')
                                    ->label('End Time'),
                                TextInput::make('note')
                                    ->label('Note / Reason')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ])
                            ->columns(['default' => 1, 'md' => 2, 'xl' => 4])
                            ->defaultItems(0)
                            ->addActionLabel('Add Date Schedule')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['schedule_date'] ?? null)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }
}
