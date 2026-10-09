<?php

namespace App\Filament\Pages;

use App\Filament\Resources\OnlineAppointments\OnlineAppointmentResource;
use App\Models\Doctor;
use App\Models\OnlineAppointment;
use App\Models\Patient;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property Schema $form
 */
class CreateAppointment extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view =

        'filament.pages.create-appointment';

    protected static ?string $navigationLabel =

        'Create Appointment';

    protected static ?string $title =

        'Create Appointment';

    protected static ?int $navigationSort = 2;

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {

        return 'CONSULTATIONS';

    }

    /**
     * Check whether the currently logged-in user

     * is a doctor.
     */
    protected static function isDoctorUser(): bool
    {

        return auth()->user()?->getActiveDoctor() !== null;

    }

    /**
     * Doctors should not see Create Appointment in navigation.
     */
    public static function shouldRegisterNavigation(): bool
    {

        $user = auth()->user();

        if (! $user) {

            return false;

        }

        // Doctorට Create Appointment menu එක පෙන්වන්න එපා

        if ($user->hasRole('doctor')) {

            return false;

        }

        return static::canAccess();

    }

    /**
     * Doctors should not access Create Appointment page directly.
     */
    public static function canAccess(array $parameters = []): bool
    {

        $user = auth()->user();

        if (! $user) {

            return false;

        }

        // Doctor cannot access Create Appointment

        if ($user->hasRole('doctor')) {

            return false;

        }

        // Other users follow existing module permission
        return $user->canAccessModule('consultations');

    }

    /**
     * Default appointment date/time.
     */
    public function mount(): void
    {

        $now = now();

        /*

         * Round the current time up to the next 5 minutes.

         *

         * Example:

         * 09:02 -> 09:05

         * 09:07 -> 09:10

         */

        $roundedMinute = (int) ceil($now->minute / 5) * 5;

        if ($roundedMinute >= 60) {

            $now->addHour()->startOfHour();

        } else {

            $now->setMinute($roundedMinute)->second(0);

        }

        $this->form->fill([

            'appointment_date' => $now->toDateString(),

            'appointment_time' => $now->format('H:i'),

            'facility_service_fee' => 0,

        ]);

    }

    public function form(Schema $schema): Schema
    {

        return $schema

            ->columns(1)
            ->extraAttributes(['class' => 'admin-form appointment-form'])

            ->components([

                Section::make('Appointment Details')

                    ->contained(false)

                    ->schema([

                        /*

                         * -------------------------------------------------

                         * PATIENT

                         * -------------------------------------------------

                         */

                        Select::make('patient_id')

                            ->label('Patient')

                            ->placeholder(

                                'Search patient by name or NIC / Passport'

                            )

                            ->required()

                            ->searchable()

                            ->getSearchResultsUsing(

                                function (string $search): array {

                                    return Patient::query()

                                        ->where(

                                            function (Builder $query) use ($search) {

                                                $query

                                                    ->where(

                                                        'full_name',

                                                        'like',

                                                        "%{$search}%"

                                                    )

                                                    ->orWhere(

                                                        'nic_or_passport',

                                                        'like',

                                                        "%{$search}%"

                                                    );

                                            }

                                        )

                                        ->orderBy('full_name')

                                        ->limit(50)

                                        ->get()

                                        ->mapWithKeys(

                                            function (

                                                Patient $patient

                                            ): array {

                                                return [

                                                    $patient->id => $patient->full_name.

                                                        ' — '.

                                                        $patient->nic_or_passport,

                                                ];

                                            }

                                        )

                                        ->all();

                                }

                            )

                            ->getOptionLabelUsing(

                                function ($value): ?string {

                                    if (! $value) {

                                        return null;

                                    }

                                    $patient =

                                        Patient::find($value);

                                    if (! $patient) {

                                        return null;

                                    }

                                    return

                                        $patient->full_name.

                                        ' — '.

                                        $patient->nic_or_passport;

                                }

                            )

                            ->native(false)

                            ->helperText(

                                'Only registered patients can be selected.'

                            ),

                        /*

                         * -------------------------------------------------

                         * DOCTOR

                         * -------------------------------------------------

                         */

                        Select::make('doctor_id')

                            ->label('Doctor')

                            ->placeholder('Select doctor')

                            ->required()

                            ->searchable()

                            ->live()

                            ->options(

                                fn (): array => Doctor::query()

                                    ->where('is_active', true)

                                    ->orderBy('name')

                                    ->pluck('name', 'id')

                                    ->all()

                            )

                            ->native(false)

                            ->helperText(

                                'Select the doctor for this appointment.'

                            ),

                        /*

                         * -------------------------------------------------

                         * CONSULTING ROOM

                         * -------------------------------------------------

                         */

                        Placeholder::make('doctor_room')

                            ->label('Consulting Room')

                            ->content(

                                function (Get $get): string {

                                    $doctorId =

                                        $get('doctor_id');

                                    if (! $doctorId) {

                                        return 'Please select a doctor';

                                    }

                                    $doctor =

                                        Doctor::query()

                                            ->select(

                                                'id',

                                                'room_number'

                                            )

                                            ->find($doctorId);

                                    if (! $doctor) {

                                        return 'Room not available';

                                    }

                                    return $doctor->room_number

                                        ? 'Room '.

                                            $doctor->room_number

                                        : 'Room Not Assigned';

                                }

                            ),

                        /*

                         * -------------------------------------------------

                         * APPOINTMENT DATE

                         * -------------------------------------------------

                         */

                        DatePicker::make('appointment_date')

                            ->label('Appointment Date')

                            ->required()

                            ->native(false)

                            ->displayFormat('d/m/Y')

                            ->minDate(

                                fn () => now()->startOfDay()

                            )

                            ->live()

                            ->rules([

                                function (

                                    Get $get

                                ): Closure {

                                    return function (

                                        string $attribute,

                                        $value,

                                        Closure $fail

                                    ) use ($get): void {

                                        $doctorId =

                                            $get('doctor_id');

                                        $time =

                                            $get('appointment_time');

                                        if (

                                            ! $doctorId ||

                                            ! $value

                                        ) {

                                            return;

                                        }

                                        try {

                                            $appointmentDate =

                                                Carbon::parse(

                                                    $value

                                                );

                                        } catch (

                                            \Throwable

                                        ) {

                                            return;

                                        }

                                        /*

                                         * If time is not selected yet,

                                         * date validation can still continue.

                                         */

                                        if (! $time) {

                                            return;

                                        }

                                        try {

                                            $appointmentDateTime =

                                                Carbon::parse(

                                                    $value.

                                                        ' '.

                                                        $time

                                                );

                                        } catch (

                                            \Throwable

                                        ) {

                                            return;

                                        }

                                        $doctor =

                                            Doctor::query()

                                                ->whereKey(

                                                    $doctorId

                                                )

                                                ->where(

                                                    'is_active',

                                                    true

                                                )

                                                ->first();

                                        if (! $doctor) {

                                            return;

                                        }

                                        /*

                                         * Prevent past appointment.

                                         */

                                        if (

                                            $appointmentDateTime->lt(

                                                now()

                                            )

                                        ) {

                                            $fail(

                                                'Appointment date and time cannot be in the past.'

                                            );

                                            return;

                                        }

                                        /*

                                         * Main doctor availability check.

                                         *

                                         * This checks:

                                         * - active status

                                         * - weekly available days

                                         * - special schedules

                                         * - leave

                                         * - temporarily unavailable

                                         * - start time

                                         * - end time

                                         */

                                        if (

                                            ! $doctor->isAvailableAt(

                                                $appointmentDateTime

                                                    ->toDateTimeString()

                                            )

                                        ) {

                                            $dayName =

                                                $appointmentDateTime

                                                    ->format('l');

                                            $fail(

                                                'Appointment cannot be created. '.

                                                    $doctor->name.

                                                    ' is not available on '.

                                                    $dayName.

                                                    ' ('.

                                                    $appointmentDateTime->format(

                                                        'd/m/Y'

                                                    ).

                                                    ') at '.

                                                    $appointmentDateTime->format(

                                                        'h:i A'

                                                    ).

                                                    '.'

                                            );

                                            return;

                                        }

                                    };

                                },

                            ])

                            ->helperText(

                                'Select the date. The selected doctor must be available on this day.'

                            ),

                        /*

                         * -------------------------------------------------

                         * APPOINTMENT TIME

                         * -------------------------------------------------

                         */

                        TimePicker::make('appointment_time')

                            ->label('Appointment Time')

                            ->required()

                            ->native(false)

                            ->seconds(false)

                            ->displayFormat('h:i A')

                            ->minutesStep(1)

                            ->live()

                            ->rules([

                                function (

                                    Get $get

                                ): Closure {

                                    return function (

                                        string $attribute,

                                        $value,

                                        Closure $fail

                                    ) use ($get): void {

                                        $doctorId =

                                            $get('doctor_id');

                                        $date =

                                            $get('appointment_date');

                                        if (

                                            ! $doctorId ||

                                            ! $date ||

                                            ! $value

                                        ) {

                                            return;

                                        }

                                        try {

                                            $appointmentDateTime =

                                                Carbon::parse(

                                                    $date.

                                                        ' '.

                                                        $value

                                                );

                                        } catch (

                                            \Throwable

                                        ) {

                                            return;

                                        }

                                        $doctor =

                                            Doctor::query()

                                                ->whereKey(

                                                    $doctorId

                                                )

                                                ->where(

                                                    'is_active',

                                                    true

                                                )

                                                ->first();

                                        if (! $doctor) {

                                            return;

                                        }

                                        /*

                                         * Cannot select past time.

                                         */

                                        if (

                                            $appointmentDateTime->lt(

                                                now()

                                            )

                                        ) {

                                            $fail(

                                                'Please select a future appointment time.'

                                            );

                                            return;

                                        }

                                        /*

                                         * Check complete doctor availability.

                                         */

                                        if (

                                            ! $doctor->isAvailableAt(

                                                $appointmentDateTime

                                                    ->toDateTimeString()

                                            )

                                        ) {

                                            $dayName =

                                                $appointmentDateTime

                                                    ->format('l');

                                            $fail(

                                                $doctor->name.

                                                    ' is not available on '.

                                                    $dayName.

                                                    ' at '.

                                                    $appointmentDateTime->format(

                                                        'h:i A'

                                                    ).

                                                    '.'

                                            );

                                            return;

                                        }

                                        /*

                                         * Check whether this exact

                                         * doctor/date/time is already booked.

                                         */

                                        $alreadyBooked =

                                            OnlineAppointment::query()

                                                ->where(

                                                    'doctor_id',

                                                    $doctor->id

                                                )

                                                ->where(

                                                    'appointment_date',

                                                    $appointmentDateTime

                                                )

                                                ->whereIn(

                                                    'status',

                                                    [

                                                        'pending',

                                                        'confirmed',

                                                    ]

                                                )

                                                ->exists();

                                        if ($alreadyBooked) {

                                            $fail(

                                                'This appointment time is already booked for '.

                                                    $doctor->name.

                                                    '. Please select another time.'

                                            );

                                        }

                                    };

                                },

                            ])

                            ->helperText(

                                'Select any time within the doctor’s working hours. Fixed time slots are not required.'

                            ),

                        TextInput::make('facility_service_fee')

                            ->label('Facilities / Service Fee')

                            ->numeric()

                            ->minValue(0)

                            ->maxValue(9999999999.99)

                            ->required()

                            ->default(0)

                            ->helperText(

                                'Optional additional fee for clinic facilities or services; entered separately from the doctor fee.'

                            ),

                        /*

                         * -------------------------------------------------

                         * NOTES

                         * -------------------------------------------------

                         */

                        Textarea::make('notes')

                            ->label('Appointment Notes')

                            ->placeholder(

                                'Optional notes about this appointment...'

                            )

                            ->rows(4)

                            ->maxLength(2000)

                            ->columnSpanFull(),

                    ])

                    ->columns(['default' => 1, 'md' => 2]),

            ])

            ->statePath('data');

    }

    public function save(): void
    {

        /*

         * Get validated form data.

         */

        $data =

            $this->form->getState();

        /*

         * -------------------------------------------------

         * PATIENT CHECK

         * -------------------------------------------------

         */

        $patient =

            Patient::query()

                ->whereKey(

                    $data['patient_id']

                )

                ->first();

        if (! $patient) {

            Notification::make()

                ->title('Patient not found')

                ->body(

                    'Please register the patient first and select the patient again.'

                )

                ->danger()

                ->send();

            return;

        }

        /*

         * -------------------------------------------------

         * DOCTOR CHECK

         * -------------------------------------------------

         */

        $doctor =

            Doctor::query()

                ->whereKey(

                    $data['doctor_id']

                )

                ->where(

                    'is_active',

                    true

                )

                ->first();

        if (! $doctor) {

            Notification::make()

                ->title('Doctor not available')

                ->body(

                    'The selected doctor is inactive or unavailable.'

                )

                ->danger()

                ->send();

            return;

        }

        /*

         * -------------------------------------------------

         * DATE + TIME

         * -------------------------------------------------

         */

        try {

            $appointmentDateTime =

                Carbon::parse(

                    $data['appointment_date'].

                        ' '.

                        $data['appointment_time']

                );

        } catch (\Throwable) {

            Notification::make()

                ->title('Invalid appointment date/time')

                ->body(

                    'Please select a valid appointment date and time.'

                )

                ->danger()

                ->send();

            return;

        }

        /*

         * -------------------------------------------------

         * PAST DATE/TIME PROTECTION

         * -------------------------------------------------

         */

        if (

            $appointmentDateTime->lt(

                now()

            )

        ) {

            Notification::make()

                ->title('Invalid appointment time')

                ->body(

                    'The appointment date and time cannot be in the past.'

                )

                ->danger()

                ->send();

            return;

        }

        /*

         * -------------------------------------------------

         * DOCTOR AVAILABILITY

         * -------------------------------------------------

         *

         * This is the main server-side protection.

         *

         * It checks:

         * - Active doctor

         * - Weekly available days

         * - Special schedule

         * - Leave

         * - Temporarily unavailable

         * - Start time

         * - End time

         */

        if (

            ! $doctor->isAvailableAt(

                $appointmentDateTime

                    ->toDateTimeString()

            )

        ) {

            $dayName =

                $appointmentDateTime

                    ->format('l');

            Notification::make()

                ->title(

                    'Doctor is not available'

                )

                ->body(

                    $doctor->name.

                        ' is not available on '.

                        $dayName.

                        ' ('.

                        $appointmentDateTime->format(

                            'd/m/Y'

                        ).

                        ') at '.

                        $appointmentDateTime->format(

                            'h:i A'

                        ).

                        '.'

                )

                ->danger()

                ->send();

            return;

        }

        /*

         * -------------------------------------------------

         * DUPLICATE BOOKING CHECK

         * -------------------------------------------------

         */

        $alreadyBooked =

            OnlineAppointment::query()

                ->where(

                    'doctor_id',

                    $doctor->id

                )

                ->where(

                    'appointment_date',

                    $appointmentDateTime

                )

                ->whereIn(

                    'status',

                    [

                        'pending',

                        'confirmed',

                    ]

                )

                ->exists();

        if ($alreadyBooked) {

            Notification::make()

                ->title(

                    'Appointment time already booked'

                )

                ->body(

                    'This doctor already has an appointment at '.

                        $appointmentDateTime->format(

                            'd/m/Y h:i A'

                        ).

                        '. Please select another time.'

                )

                ->warning()

                ->send();

            return;

        }

        /*

         * -------------------------------------------------

         * TOKEN NUMBER

         * -------------------------------------------------

         */

        $tokenNumber =

            OnlineAppointment::getNextTokenNumber(

                $appointmentDateTime

            );

        /*

         * -------------------------------------------------

         * BOOKING NUMBER

         * -------------------------------------------------

         */

        $bookingNumber =

            'REC-'.

            now()->format(

                'YmdHis'

            ).

            '-'.

            Str::upper(

                Str::random(4)

            );

        /*

         * -------------------------------------------------

         * CREATE APPOINTMENT

         * -------------------------------------------------

         */

        $appointment =

            OnlineAppointment::create([

                'patient_id' => $patient->id,

                'booking_number' => $bookingNumber,

                'token_number' => $tokenNumber,

                'full_name' => $patient->full_name,

                'nic_or_passport' => $patient->nic_or_passport,

                'phone_number' => $patient->phone_number,

                'email' => $patient->email,

                'patient_type' => $patient->patient_type,

                'doctor_id' => $doctor->id,

                /*

                 * Save combined Date + Time.

                 */

                'appointment_date' => $appointmentDateTime,

                'facility_service_fee' => round(

                    (float) $data['facility_service_fee'],

                    2

                ),

                'notes' => $data['notes'] ?? null,

                'status' => 'confirmed',

                'source' => 'Reception',

            ]);

        /*

         * -------------------------------------------------

         * CREATE INVOICE

         * -------------------------------------------------

         */

        $appointment->ensureInvoice();

        /*

         * -------------------------------------------------

         * SUCCESS NOTIFICATION

         * -------------------------------------------------

         */

        Notification::make()

            ->title(

                'Appointment created successfully'

            )

            ->body(

                'Token No: '.

                    str_pad(

                        (string)

                            $appointment->token_number,

                        2,

                        '0',

                        STR_PAD_LEFT

                    ).

                    ' | Booking: '.

                    $appointment->booking_number.

                    ' | Time: '.

                    $appointmentDateTime->format(

                        'd/m/Y h:i A'

                    ).

                    ' | Invoice: '.

                    $appointment->invoice_number

            )

            ->success()

            ->send();

        /*

         * -------------------------------------------------

         * REDIRECT

         * -------------------------------------------------

         */

        $this->redirect(

            OnlineAppointmentResource::getUrl()

        );

    }
}
