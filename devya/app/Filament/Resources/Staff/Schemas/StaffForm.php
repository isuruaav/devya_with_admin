<?php

namespace App\Filament\Resources\Staff\Schemas;

use App\Models\Department;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->extraAttributes(['class' => 'admin-form staff-form'])
            ->components([

                // =========================================================
                // STAFF IDENTIFICATION
                // =========================================================

                Section::make('Staff Identification')
                    ->contained(false)
                    ->icon('heroicon-o-identification')
                    ->schema([

                        TextInput::make('staff_code')
                            ->label('Staff ID')
                            ->disabled()
                            ->dehydrated()
                            ->placeholder('Automatically generated')
                            ->maxLength(50),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                            ])
                            ->default('Active')
                            ->required(),

                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                // =========================================================
                // PERSONAL DETAILS
                // =========================================================

                Section::make('Personal Details')
                    ->contained(false)
                    ->icon('heroicon-o-user')
                    ->schema([

                        TextInput::make('full_name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('name_with_initials')
                            ->label('Name with Initials')
                            ->maxLength(255),

                        TextInput::make('nic_passport')
                            ->label('NIC / Passport No.')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),

                        DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->maxDate(now()),

                        Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                                'Other' => 'Other',
                            ])
                            ->default('Male')
                            ->required(),

                        FileUpload::make('photo')
                            ->label('Staff Photo')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('staff/photos')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->nullable()
                            ->columnSpanFull(),

                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                // =========================================================
                // CONTACT INFORMATION
                // =========================================================

                Section::make('Contact Information')
                    ->contained(false)
                    ->icon('heroicon-o-phone')
                    ->schema([

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(20),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(255),

                        Textarea::make('address')
                            ->label('Address')
                            ->rows(3)
                            ->columnSpanFull(),

                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                // =========================================================
                // EMERGENCY CONTACT
                // =========================================================

                Section::make('Emergency Contact')
                    ->contained(false)
                    ->icon('heroicon-o-shield-exclamation')
                    ->schema([

                        TextInput::make('emergency_contact_name')
                            ->label('Contact Name')
                            ->maxLength(255),

                        TextInput::make('emergency_contact_phone')
                            ->label('Contact Phone')
                            ->tel()
                            ->maxLength(20),

                        TextInput::make('emergency_contact_relationship')
                            ->label('Relationship')
                            ->maxLength(100),

                    ])
                    ->columns(['default' => 1, 'md' => 2, 'xl' => 3]),

                // =========================================================
                // EMPLOYMENT DETAILS
                // =========================================================

                Section::make('Employment Details')
                    ->contained(false)
                    ->icon('heroicon-o-briefcase')
                    ->schema([

                        TextInput::make('designation')
                            ->label('Designation')
                            ->required()
                            ->maxLength(255),

                        Select::make('department_id')
                            ->label('Department')
                            ->options(
                                fn () => Department::query()
                                    ->where('status', true)
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Select Department'),

                        DatePicker::make('joining_date')
                            ->label('Joining Date')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->required()
                            ->maxDate(now()),

                        Select::make('employment_type')
                            ->label('Employment Type')
                            ->options([
                                'Permanent' => 'Permanent',
                                'Temporary' => 'Temporary',
                                'Contract' => 'Contract',
                                'Part Time' => 'Part Time',
                            ])
                            ->default('Permanent')
                            ->required(),

                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                // =========================================================
                // SALARY DETAILS
                // =========================================================

                Section::make('Salary Details')
                    ->contained(false)
                    ->icon('heroicon-o-banknotes')
                    ->schema([

                        TextInput::make('basic_salary')
                            ->label('Basic Salary')
                            ->numeric()
                            ->prefix('LKR')
                            ->default(0)
                            ->required()
                            ->minValue(0),

                        TextInput::make('allowance')
                            ->label('Allowance')
                            ->numeric()
                            ->prefix('LKR')
                            ->default(0)
                            ->required()
                            ->minValue(0),

                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                // =========================================================
                // ADDITIONAL INFORMATION
                // =========================================================

                Section::make('Additional Information')
                    ->contained(false)
                    ->icon('heroicon-o-document-text')
                    ->schema([

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(4)
                            ->columnSpanFull(),

                    ]),

            ]);
    }
}
