<?php

namespace App\Filament\Resources\Consultations\Schemas;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Treatment;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ConsultationForm
{
    public static function configure(

        Schema $schema

    ): Schema {

        return $schema

            ->components([

                /*

                |--------------------------------------------------------------------------

                | CONSULTATION INFORMATION

                |--------------------------------------------------------------------------

                */

                Section::make()

                    ->schema([

                        Grid::make(12)

                            ->schema([

                                TextInput::make('consultation_number')

                                    ->label('Consultation No')

                                    ->placeholder('Auto generated')

                                    ->readOnly()

                                    ->dehydrated()

                                    ->extraInputAttributes([

                                        'class' => 'font-bold text-emerald-700 bg-emerald-50',

                                    ])

                                    ->columnSpan([

                                        'default' => 12,

                                        'sm' => 6,

                                        'lg' => 3,

                                    ]),

                                DateTimePicker::make('consultation_date')

                                    ->label('Date & Time')

                                    ->seconds(false)

                                    ->native(false)

                                    ->readOnly()

                                    ->dehydrated()

                                    ->columnSpan([

                                        'default' => 12,

                                        'sm' => 6,

                                        'lg' => 3,

                                    ]),

                                TextInput::make('patient_type')

                                    ->label('Patient Type')

                                    ->default('Local')

                                    ->readOnly()

                                    ->dehydrated()

                                    ->live()

                                    ->afterStateUpdated(

                                        function (

                                            Set $set,

                                            Get $get

                                        ): void {

                                            self::updateDoctorFee(

                                                $set,

                                                $get

                                            );

                                            self::recalculateTotals(

                                                $set,

                                                $get

                                            );

                                        }

                                    )

                                    ->afterStateHydrated(

                                        function (

                                            Set $set,

                                            Get $get

                                        ): void {

                                            self::updateDoctorFee(

                                                $set,

                                                $get

                                            );

                                            self::recalculateTotals(

                                                $set,

                                                $get

                                            );

                                        }

                                    )

                                    ->extraInputAttributes([

                                        'class' => 'font-semibold text-gray-700 bg-gray-50',

                                    ])

                                    ->columnSpan([

                                        'default' => 12,

                                        'sm' => 6,

                                        'lg' => 3,

                                    ]),

                                TextInput::make('currency')

                                    ->label('Currency')

                                    ->default('LKR')

                                    ->readOnly()

                                    ->dehydrated()

                                    ->extraInputAttributes([

                                        'class' => 'font-semibold text-gray-700 bg-gray-50',

                                    ])

                                    ->columnSpan([

                                        'default' => 12,

                                        'sm' => 6,

                                        'lg' => 3,

                                    ]),

                            ]),

                    ])

                    ->columnSpanFull()

                    ->extraAttributes([

                        'class' => 'border border-gray-200 border-l-4 border-l-emerald-500 bg-white shadow-sm rounded-xl',

                    ]),

                /*

                |--------------------------------------------------------------------------

                | PATIENT & DOCTOR

                |--------------------------------------------------------------------------

                */

                Section::make('Patient & Doctor')

                    ->description(

                        'Select the patient and doctor responsible for this consultation.'

                    )

                    ->icon('heroicon-o-user-group')

                    ->schema([

                        Grid::make(12)

                            ->schema([

                                TextInput::make('patient_display')

                                    ->label('Patient')

                                    ->default('')

                                    ->formatStateUsing(

                                        function (Get $get): string {

                                            $patientId =

                                                $get('patient_id');

                                            if (! $patientId) {

                                                return '';

                                            }

                                            $patient = Patient::query()->find($patientId);

                                            if (! $patient) {
                                                return '';
                                            }

                                            return (string) $patient->full_name;

                                        }

                                    )

                                    ->readOnly()

                                    ->dehydrated(false)

                                    ->extraInputAttributes([

                                        'class' => 'font-semibold text-gray-700 bg-gray-50',

                                    ])

                                    ->columnSpan([

                                        'default' => 12,

                                        'lg' => 6,

                                    ]),

                                Hidden::make('patient_id')

                                    ->dehydrated(),

                                TextInput::make('doctor_display')

                                    ->label('Consultation Doctor')

                                    ->default('')

                                    ->formatStateUsing(

                                        function (Get $get): string {

                                            $doctorId =

                                                $get('doctor_id');

                                            if (! $doctorId) {

                                                return '';

                                            }

                                            $doctor = Doctor::query()->find($doctorId);

                                            if (! $doctor) {
                                                return '';
                                            }

                                            return (string) $doctor->name;

                                        }

                                    )

                                    ->readOnly()

                                    ->dehydrated(false)

                                    ->extraInputAttributes([

                                        'class' => 'font-semibold text-gray-700 bg-gray-50',

                                    ])

                                    ->columnSpan([

                                        'default' => 12,

                                        'lg' => 6,

                                    ]),

                                Hidden::make('doctor_id')

                                    ->dehydrated()

                                    ->live()

                                    ->afterStateUpdated(

                                        function (

                                            Set $set,

                                            Get $get

                                        ): void {

                                            self::updateDoctorFee(

                                                $set,

                                                $get

                                            );

                                            self::recalculateTotals(

                                                $set,

                                                $get

                                            );

                                        }

                                    ),

                            ]),

                    ])

                    ->columnSpanFull()

                    ->extraAttributes([

                        'class' => 'border border-gray-200 bg-white shadow-sm rounded-xl',

                    ]),

                /*

                |--------------------------------------------------------------------------

                | CONSULTATION CHARGES

                |--------------------------------------------------------------------------

                */

                Section::make('Consultation Charges')

                    ->description(

                        'Doctor consultation fee and treatment charges.'

                    )

                    ->icon('heroicon-o-banknotes')

                    ->schema([

                        Grid::make(12)

                            ->schema([

                                TextInput::make('doctor_fee')

                                    ->label('Doctor Consultation Fee')

                                    ->numeric()

                                    ->prefix(

                                        fn (Get $get) => $get('currency') ?? 'LKR'

                                    )

                                    ->readOnly()

                                    ->dehydrated()

                                    ->columnSpan([

                                        'default' => 12,

                                        'md' => 4,

                                    ]),

                                TextInput::make('treatment_total')

                                    ->label('Treatment Total')

                                    ->numeric()

                                    ->prefix(

                                        fn (Get $get) => $get('currency') ?? 'LKR'

                                    )

                                    ->readOnly()

                                    ->dehydrated()

                                    ->columnSpan([

                                        'default' => 12,

                                        'md' => 4,

                                    ]),

                                TextInput::make('grand_total')

                                    ->label('Gross Consultation Total')

                                    ->numeric()

                                    ->prefix(

                                        fn (Get $get) => $get('currency') ?? 'LKR'

                                    )

                                    ->readOnly()

                                    ->dehydrated()

                                    ->extraInputAttributes([

                                        'class' => 'font-bold text-xl text-emerald-700 bg-emerald-50 border-emerald-200',

                                    ])

                                    ->columnSpan([

                                        'default' => 12,

                                        'md' => 4,

                                    ]),

                            ]),

                    ])

                    ->columnSpanFull()

                    ->extraAttributes([

                        'class' => 'border border-emerald-200 border-l-4 border-l-emerald-500 bg-white shadow-sm rounded-xl',

                    ]),

                /*

                |--------------------------------------------------------------------------

                | TREATMENTS

                |--------------------------------------------------------------------------

                */

                Section::make('Treatments')

                    ->description(

                        'Add treatments provided during this consultation.'

                    )

                    ->icon('heroicon-o-sparkles')

                    ->schema([

                        Repeater::make('treatments')

                            ->relationship('treatments')

                            ->schema([

                                Select::make('treatment_id')

                                    ->label('Treatment')

                                    ->placeholder('Select treatment...')

                                    ->relationship(

                                        'treatment',

                                        'name'

                                    )

                                    ->searchable()

                                    ->preload()

                                    ->required()

                                    ->native(false)

                                    ->live()

                                    ->afterStateUpdated(

                                        function (

                                            Set $set,

                                            Get $get

                                        ): void {

                                            self::updateTreatmentPrice(

                                                $set,

                                                $get

                                            );

                                            self::recalculateTotals(

                                                $set,

                                                $get

                                            );

                                        }

                                    )

                                    ->columnSpan([

                                        'default' => 12,

                                        'md' => 5,

                                    ]),

                                TextInput::make('quantity')

                                    ->label('Qty')

                                    ->numeric()

                                    ->default(1)

                                    ->minValue(1)

                                    ->required()

                                    ->live()

                                    ->extraInputAttributes([

                                        'class' => 'text-center font-semibold',

                                    ])

                                    ->afterStateUpdated(

                                        function (

                                            Set $set,

                                            Get $get

                                        ): void {

                                            self::updateTreatmentPrice(

                                                $set,

                                                $get

                                            );

                                            self::recalculateTotals(

                                                $set,

                                                $get

                                            );

                                        }

                                    )

                                    ->columnSpan([

                                        'default' => 12,

                                        'sm' => 4,

                                        'md' => 2,

                                    ]),

                                TextInput::make('price')

                                    ->label('Unit Price')

                                    ->numeric()

                                    ->prefix(

                                        fn (Get $get) => $get('../../currency') ?? 'LKR'

                                    )

                                    ->readOnly()

                                    ->dehydrated()

                                    ->columnSpan([

                                        'default' => 12,

                                        'sm' => 4,

                                        'md' => 2,

                                    ]),

                                /*

                                |--------------------------------------------------------------------------

                                | IMPORTANT:

                                | Database column is "cost", NOT "total_price"

                                |--------------------------------------------------------------------------

                                */

                                TextInput::make('cost')

                                    ->label('Total')

                                    ->numeric()

                                    ->prefix(

                                        fn (Get $get) => $get('../../currency') ?? 'LKR'

                                    )

                                    ->readOnly()

                                    ->dehydrated()

                                    ->extraInputAttributes([

                                        'class' => 'font-semibold text-emerald-700',

                                    ])

                                    ->columnSpan([

                                        'default' => 12,

                                        'sm' => 4,

                                        'md' => 3,

                                    ]),

                            ])

                            ->columns(12)

                            ->defaultItems(0)

                            ->addActionLabel('Add Treatment')

                            ->live()

                            ->afterStateUpdated(

                                function (

                                    Set $set,

                                    Get $get

                                ): void {

                                    self::recalculateTotals(

                                        $set,

                                        $get

                                    );

                                }

                            )

                            ->columnSpanFull(),

                    ])

                    ->columnSpanFull()

                    ->extraAttributes([

                        'class' => 'border border-gray-200 bg-white shadow-sm rounded-xl',

                    ]),

                /*

                |--------------------------------------------------------------------------

                | PRESCRIPTION

                |--------------------------------------------------------------------------

                */

                Section::make('Prescription')

                    ->description(

                        'Prescribe medicines for the patient. Pharmacy billing is handled separately.'

                    )

                    ->icon('heroicon-o-beaker')

                    ->schema([

                        Repeater::make('medicines')

                            ->relationship('medicines')

                            ->schema([

                                Select::make('medicine_id')

                                    ->label('Medicine')

                                    ->placeholder('Search medicine...')

                                    ->relationship(

                                        'medicine',

                                        'name'

                                    )

                                    ->searchable()

                                    ->preload()

                                    ->required()

                                    ->native(false)

                                    ->columnSpan([

                                        'default' => 12,

                                        'md' => 9,

                                    ]),

                                TextInput::make('quantity')

                                    ->label('Quantity')

                                    ->numeric()

                                    ->default(1)

                                    ->minValue(1)

                                    ->required()

                                    ->dehydrateStateUsing(

                                        fn ($state) => blank($state)

                                                ? 1

                                                : (int) $state

                                    )

                                    ->extraInputAttributes([

                                        'class' => 'text-center font-bold',

                                    ])

                                    ->columnSpan([

                                        'default' => 12,

                                        'md' => 3,

                                    ]),

                            ])

                            ->columns(12)

                            ->defaultItems(0)

                            ->addActionLabel('Add Medicine')

                            ->columnSpanFull(),

                    ])

                    ->columnSpanFull()

                    ->extraAttributes([

                        'class' => 'border border-blue-200 border-l-4 border-l-blue-500 bg-white shadow-sm rounded-xl',

                    ]),

                /*

                |--------------------------------------------------------------------------

                | DOCTOR NOTES

                |--------------------------------------------------------------------------

                */

                Section::make('Doctor Notes')

                    ->description(

                        'Clinical notes, instructions and recommendations.'

                    )

                    ->icon('heroicon-o-document-text')

                    ->schema([

                        Textarea::make('notes')

                            ->label('Consultation Notes')

                            ->placeholder(

                                'Enter consultation notes and instructions...'

                            )

                            ->rows(5)

                            ->autosize()

                            ->columnSpanFull(),

                    ])

                    ->columnSpanFull()

                    ->extraAttributes([

                        'class' => 'border border-gray-200 bg-white shadow-sm rounded-xl',

                    ]),

                /*

                |--------------------------------------------------------------------------

                | FINAL BILLING SUMMARY

                |--------------------------------------------------------------------------

                */

                Section::make('Billing Summary')

                    ->description(

                        'Final consultation amount. Medicine charges are excluded.'

                    )

                    ->icon('heroicon-o-receipt-percent')

                    ->schema([

                        Grid::make(12)

                            ->schema([

                                TextInput::make('doctor_fee')

                                    ->label('Doctor Fee')

                                    ->numeric()

                                    ->prefix(

                                        fn (Get $get) => $get('currency') ?? 'LKR'

                                    )

                                    ->readOnly()

                                    ->dehydrated()

                                    ->columnSpan([

                                        'default' => 12,

                                        'md' => 4,

                                    ]),

                                TextInput::make('treatment_total')

                                    ->label('Treatment Total')

                                    ->numeric()

                                    ->prefix(

                                        fn (Get $get) => $get('currency') ?? 'LKR'

                                    )

                                    ->readOnly()

                                    ->dehydrated()

                                    ->columnSpan([

                                        'default' => 12,

                                        'md' => 4,

                                    ]),

                                TextInput::make('grand_total')

                                    ->label('TOTAL PAYABLE')

                                    ->numeric()

                                    ->prefix(

                                        fn (Get $get) => $get('currency') ?? 'LKR'

                                    )

                                    ->readOnly()

                                    ->dehydrated()

                                    ->extraInputAttributes([

                                        'class' => 'font-black text-2xl text-emerald-700 bg-emerald-50 border-emerald-300',

                                    ])

                                    ->columnSpan([

                                        'default' => 12,

                                        'md' => 4,

                                    ]),

                            ]),

                    ])

                    ->columnSpanFull()

                    ->extraAttributes([

                        'class' => 'border-2 border-emerald-300 bg-emerald-50/50 shadow-md rounded-xl',

                    ]),

            ]);

    }

    /*

    |--------------------------------------------------------------------------

    | UPDATE DOCTOR FEE

    |--------------------------------------------------------------------------

    */

    protected static function updateDoctorFee(

        Set $set,

        Get $get

    ): void {

        $doctorId = $get('doctor_id');

        if (! $doctorId) {

            $set('doctor_fee', 0);

            return;

        }

        $doctor = Doctor::find($doctorId);

        if (! $doctor) {

            $set('doctor_fee', 0);

            return;

        }

        $set(

            'doctor_fee',

            $doctor->consultationFeeFor(

                (string) ($get('patient_type') ?? 'Local')

            )

        );

    }

    /*

    |--------------------------------------------------------------------------

    | UPDATE TREATMENT PRICE

    |--------------------------------------------------------------------------

    |

    | Existing database column:

    |

    | consultation_treatments.cost

    |

    | Therefore we NEVER use total_price here.

    |--------------------------------------------------------------------------

    */

    protected static function updateTreatmentPrice(

        Set $set,

        Get $get

    ): void {

        $treatmentId = $get('treatment_id');

        if (! $treatmentId) {

            $set('price', 0);

            $set('cost', 0);

            return;

        }

        $treatment = Treatment::find($treatmentId);

        if (! $treatment) {

            $set('price', 0);

            $set('cost', 0);

            return;

        }

        $patientType =

            strtolower(

                (string) (

                    $get('../../patient_type')

                    ?? 'local'

                )

            );

        /*

        |--------------------------------------------------------------------------

        | PRICE

        |--------------------------------------------------------------------------

        */

        if ($patientType === 'foreign') {

            $price =

                $treatment->foreign_price

                ?? $treatment->foreign_fee

                ?? 0;

        } else {

            $price =

                $treatment->local_price

                ?? $treatment->price

                ?? $treatment->local_fee

                ?? 0;

        }

        $quantity =

            (float) (

                $get('quantity')

                ?? 1

            );

        if ($quantity < 1) {

            $quantity = 1;

        }

        $total =

            $price * $quantity;

        $set(

            'price',

            $price

        );

        /*

        |--------------------------------------------------------------------------

        | SAVE TO EXISTING "cost" COLUMN

        |--------------------------------------------------------------------------

        */

        $set(

            'cost',

            $total

        );

    }

    /*

    |--------------------------------------------------------------------------

    | RECALCULATE ALL TOTALS

    |--------------------------------------------------------------------------

    */

    protected static function recalculateTotals(

        Set $set,

        Get $get

    ): void {

        $doctorFee =

            (float) (

                $get('doctor_fee')

                ?? 0

            );

        $treatments =

            $get('treatments')

            ?? [];

        $treatmentTotal = 0;

        foreach (

            $treatments as $index => $row

        ) {

            $treatmentId =

                $row['treatment_id']

                ?? null;

            if (! $treatmentId) {

                continue;

            }

            $treatment =

                Treatment::find($treatmentId);

            if (! $treatment) {

                continue;

            }

            $patientType =

                strtolower(

                    (string) (

                        $get('patient_type')

                        ?? 'local'

                    )

                );

            if ($patientType === 'foreign') {

                $price =

                    $treatment->foreign_price

                    ?? $treatment->foreign_fee

                    ?? 0;

            } else {

                $price =

                    $treatment->local_price

                    ?? $treatment->price

                    ?? $treatment->local_fee

                    ?? 0;

            }

            $quantity =

                (float) (

                    $row['quantity']

                    ?? 1

                );

            if ($quantity < 1) {

                $quantity = 1;

            }

            $total =

                $price * $quantity;

            /*

            |--------------------------------------------------------------------------

            | Update current repeater row

            |--------------------------------------------------------------------------

            */

            $set(

                "treatments.{$index}.price",

                $price

            );

            $set(

                "treatments.{$index}.cost",

                $total

            );

            $treatmentTotal +=

                $total;

        }

        /*

        |--------------------------------------------------------------------------

        | MEDICINE TOTAL

        |--------------------------------------------------------------------------

        |

        | Medicine charges are handled separately by Pharmacy.

        |

        */

        $medicineTotal = 0;

        /*

        |--------------------------------------------------------------------------

        | GRAND TOTAL

        |--------------------------------------------------------------------------

        */

        $grandTotal =

            $doctorFee

            +

            $treatmentTotal;

        $set(

            'treatment_total',

            $treatmentTotal

        );

        $set(

            'medicine_total',

            $medicineTotal

        );

        $set(

            'grand_total',

            $grandTotal

        );

    }
}
