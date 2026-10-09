<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\OnlineAppointment;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OnlineAppointmentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW ONLINE APPOINTMENT FORM
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        return view('online-appointments.create', [
            'doctors' => Doctor::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                    'specialization',
                ]),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE ONLINE APPOINTMENT
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE FORM DATA
        |--------------------------------------------------------------------------
        */

        $data = $request->validate([
            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'nic_or_passport' => [
                'required',
                'string',
                'max:100',
            ],

            'phone_number' => [
                'required',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'patient_type' => [
                'required',
                'in:Local,Foreign',
            ],

            'doctor_id' => [
                'required',
                'integer',
                'exists:doctors,id',
            ],

            'appointment_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | GET ACTIVE DOCTOR
        |--------------------------------------------------------------------------
        */

        $doctor = Doctor::query()
            ->where(
                'is_active',
                true
            )
            ->find(
                $data['doctor_id']
            );

        if (! $doctor) {
            throw ValidationException::withMessages([
                'doctor_id' => 'Selected doctor is not available.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | APPOINTMENT DATE / TIME
        |--------------------------------------------------------------------------
        */

        $appointment = Carbon::parse(
            $data['appointment_date']
        );

        /*
        |--------------------------------------------------------------------------
        | CREATE UNSAVED APPOINTMENT OBJECT
        |--------------------------------------------------------------------------
        |
        | We validate the doctor using the same method used by the model.
        |
        */

        $onlineAppointment =
            new OnlineAppointment;

        $onlineAppointment->doctor_id =
            $doctor->id;

        $onlineAppointment->appointment_date =
            $appointment;

        /*
        |--------------------------------------------------------------------------
        | CHECK DOCTOR AVAILABILITY
        |--------------------------------------------------------------------------
        |
        | This will check:
        |
        | - doctor is active
        | - availability_status = available
        | - special date schedule
        | - weekly available_days
        | - schedule_start
        | - schedule_end
        |
        | Therefore, if Thursday is NOT in available_days,
        | the appointment will NOT be created on Thursday.
        |
        */

        $onlineAppointment->validateDoctorAvailability();

        /*
        |--------------------------------------------------------------------------
        | DAILY PATIENT CAPACITY
        |--------------------------------------------------------------------------
        */

        $booked = OnlineAppointment::query()
            ->where(
                'doctor_id',
                $doctor->id
            )
            ->whereDate(
                'appointment_date',
                $appointment->toDateString()
            )
            ->whereIn(
                'status',
                [
                    'pending',
                    'confirmed',
                ]
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | CONSULTATION COUNT
        |--------------------------------------------------------------------------
        */

        $consultationBooked =
            $doctor->consultations()
                ->whereDate(
                    'consultation_date',
                    $appointment->toDateString()
                )
                ->where(
                    'status',
                    '!=',
                    'cancelled'
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | CHECK DAILY LIMIT
        |--------------------------------------------------------------------------
        */

        if (
            $doctor->max_patients_per_day !== null
            &&
            (
                $booked +
                $consultationBooked
            ) >=
                $doctor->max_patients_per_day
        ) {
            throw ValidationException::withMessages([
                'appointment_date' => 'The selected doctor has reached the daily patient capacity.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE ACTUAL APPOINTMENT
        |--------------------------------------------------------------------------
        */

        $onlineAppointment = OnlineAppointment::create([
            ...$data,

            'booking_number' => 'WEB-'.
                str_pad(
                    (string) (
                        OnlineAppointment::max('id') + 1
                    ),
                    6,
                    '0',
                    STR_PAD_LEFT
                ),

            'appointment_date' => $appointment,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SUCCESS PAGE
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'online-appointments.success',
                $onlineAppointment
            )
            ->with(
                'booking_number',
                $onlineAppointment->booking_number
            );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE WEBSITE APPOINTMENT REQUEST
    |--------------------------------------------------------------------------
    |
    | The public website lives outside this Laravel app, so it posts the
    | service-level request here and Reception assigns the doctor later.
    |
    */

    public function storeWebsite(
        Request $request
    ): JsonResponse|RedirectResponse {
        if (filled($request->input('website'))) {
            throw ValidationException::withMessages([
                'form' => 'Unable to submit this appointment request.',
            ]);
        }

        $data = $request->validate([
            'fullName' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:50',
            ],

            'service' => [
                'required',
                'string',
                'max:255',
            ],

            'preferredDate' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'preferredTime' => [
                'nullable',
                'string',
                'max:50',
            ],

            'contactMethod' => [
                'nullable',
                'string',
                'in:WhatsApp,Phone call,SMS',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1500',
            ],
        ]);

        $preferredTime =
            $data['preferredTime']
            ?? 'Any available time';

        $appointmentDate = Carbon::parse(
            $data['preferredDate'].' '.
                $this->preferredTimeToClock(
                    $preferredTime
                )
        );

        $systemNotes = [
            'Requested service: '.$data['service'],
            'Preferred time: '.$preferredTime,
            'Preferred response: '.($data['contactMethod'] ?? 'WhatsApp'),
            'Submitted from: DEVYA CEYLON website',
        ];

        if (filled($data['notes'] ?? null)) {
            $systemNotes[] = 'Notes: '.trim(
                (string) $data['notes']
            );
        }

        $fingerprint = hash('sha256', json_encode([
            Str::lower(trim($data['fullName'])),
            preg_replace('/\D+/', '', $data['phone']),
            Str::lower(trim($data['service'])),
            $appointmentDate->toDateTimeString(),
            $data['contactMethod'] ?? 'WhatsApp',
            trim($data['notes'] ?? ''),
        ], JSON_THROW_ON_ERROR));

        try {
            $onlineAppointment = Cache::lock('website-appointment:'.$fingerprint, 10)->block(5,
                fn (): OnlineAppointment => DB::transaction(function () use ($fingerprint, $data, $appointmentDate, $systemNotes): OnlineAppointment {
                    $existing = OnlineAppointment::query()
                        ->where('website_request_fingerprint', $fingerprint)
                        ->where('source', 'Website')
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->where('created_at', '>=', now()->subMinutes(20))
                        ->first();

                    if ($existing) {
                        return $existing;
                    }

                    return OnlineAppointment::create([
                        'booking_number' => $this->generateWebsiteBookingNumber(),
                        'full_name' => trim($data['fullName']),
                        'nic_or_passport' => 'WEBSITE-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
                        'phone_number' => trim($data['phone']),
                        'email' => null,
                        'patient_type' => 'Local',
                        'doctor_id' => null,
                        'appointment_date' => $appointmentDate,
                        'notes' => implode(PHP_EOL, $systemNotes),
                        'status' => 'pending',
                        'source' => 'Website',
                        'website_request_fingerprint' => $fingerprint,
                    ]);
                }),
            );
        } catch (LockTimeoutException) {
            throw ValidationException::withMessages(['form' => 'This request is being processed. Please try again shortly.']);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Appointment request saved.',
                'booking_number' => $onlineAppointment->booking_number,
                'appointment_id' => $onlineAppointment->id,
            ], 201);
        }

        return redirect()
            ->route(
                'online-appointments.success',
                $onlineAppointment
            )
            ->with(
                'booking_number',
                $onlineAppointment->booking_number
            );
    }

    protected function preferredTimeToClock(
        string $preferredTime
    ): string {
        return match ($preferredTime) {
            'Afternoon' => '13:00:00',
            'Evening' => '17:00:00',
            default => '09:00:00',
        };
    }

    protected function generateWebsiteBookingNumber(): string
    {
        do {
            $bookingNumber =
                'WEB-'.
                now()->format('YmdHis').
                '-'.
                Str::upper(Str::random(4));
        } while (
            OnlineAppointment::query()
                ->where(
                    'booking_number',
                    $bookingNumber
                )
                ->exists()
        );

        return $bookingNumber;
    }

    /*
    |--------------------------------------------------------------------------
    | SUCCESS PAGE
    |--------------------------------------------------------------------------
    */

    public function success(
        OnlineAppointment $onlineAppointment
    ): View {
        return view(
            'online-appointments.success',
            compact('onlineAppointment')
        );
    }
}
