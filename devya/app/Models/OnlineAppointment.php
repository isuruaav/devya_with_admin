<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class OnlineAppointment extends Model
{
    protected $guarded = [];

    protected $casts = [

        'appointment_date' => 'datetime',

        'reviewed_at' => 'datetime',

        'invoice_issued_at' => 'datetime',

        'paid_at' => 'datetime',

        'appointment_fee' => 'decimal:2',

        'facility_service_fee' => 'decimal:2',

        'token_number' => 'integer',

    ];

    public function getInvoiceTotalAttribute(): float
    {

        return round(

            (float) $this->appointment_fee

                + (float) $this->facility_service_fee,

            2

        );

    }

    /*

    |--------------------------------------------------------------------------

    | GET NEXT TOKEN NUMBER

    |--------------------------------------------------------------------------

    |

    | Token numbering starts from 01 for each appointment date.

    |

    */

    public static function getNextTokenNumber(

        string|\DateTimeInterface $appointmentDate

    ): int {

        $date = Carbon::parse(

            $appointmentDate

        )->toDateString();

        $lastToken = static::query()

            ->whereDate(

                'appointment_date',

                $date

            )

            ->whereNotNull(

                'token_number'

            )

            ->max(

                'token_number'

            );

        return ((int) $lastToken) + 1;

    }

    /*

    |--------------------------------------------------------------------------

    | VALIDATE DOCTOR AVAILABILITY

    |--------------------------------------------------------------------------

    |

    | This method checks the selected doctor's:

    |

    | 1. Active status

    | 2. Availability status

    | 3. Date-specific schedule

    | 4. Weekly schedule

    | 5. Working time for the selected day

    |

    | Date-specific schedule has priority over weekly schedule.

    |

    | This method is intentionally NOT a global saving() event.

    | Call it from the actual appointment creation/update process.

    |

    */

    public function validateDoctorAvailability(): void
    {

        /*

        |--------------------------------------------------------------------------

        | BASIC VALIDATION

        |--------------------------------------------------------------------------

        */

        if (

            empty($this->doctor_id) ||

            empty($this->appointment_date)

        ) {

            return;

        }

        /*

        |--------------------------------------------------------------------------

        | GET DOCTOR

        |--------------------------------------------------------------------------

        */

        $doctor = $this->relationLoaded('doctor')

            ? $this->doctor

            : Doctor::query()->find(

                $this->doctor_id

            );

        if (! $doctor) {

            throw ValidationException::withMessages([
                'doctor_id' => 'Selected doctor was not found.',

            ]);

        }

        /*

        |--------------------------------------------------------------------------

        | CONVERT APPOINTMENT DATE/TIME

        |--------------------------------------------------------------------------

        */

        $appointmentDateTime =

            $this->appointment_date instanceof Carbon

                ? $this->appointment_date

                : Carbon::parse(

                    (string) $this->appointment_date

                );

        /*

        |--------------------------------------------------------------------------

        | CHECK DOCTOR AVAILABILITY

        |--------------------------------------------------------------------------

        |

        | Doctor::isAvailableAt() handles:

        |

        | - Doctor active status

        | - Doctor availability status

        | - Date-specific schedule

        | - Date-specific leave

        | - Weekly schedule

        | - Day-specific working time

        |

        */

        if (

            ! $doctor->isAvailableAt(

                $appointmentDateTime->toDateTimeString()

            )

        ) {

            throw ValidationException::withMessages([
                'appointment_date' => 'The selected doctor is not available on the selected date or time.',

            ]);

        }

    }

    /*

    |--------------------------------------------------------------------------

    | PATIENT

    |--------------------------------------------------------------------------

    */

    /** @return BelongsTo<Patient, $this> */
    public function patient(): BelongsTo
    {

        return $this->belongsTo(

            Patient::class

        );

    }

    /*

    |--------------------------------------------------------------------------

    | DOCTOR

    |--------------------------------------------------------------------------

    */

    /** @return BelongsTo<Doctor, $this> */
    public function doctor(): BelongsTo
    {

        return $this->belongsTo(

            Doctor::class

        );

    }

    /*

    |--------------------------------------------------------------------------

    | TRANSFER TO DOCTOR

    |--------------------------------------------------------------------------

    */

    public function transferToDoctor(Doctor $doctor): self
    {

        $this->doctor_id = $doctor->getKey();

        $this->save();

        if ($this->consultation_id) {

            Consultation::query()

                ->whereKey(

                    $this->consultation_id

                )

                ->update([

                    'doctor_id' => $doctor->getKey(),

                ]);

        }

        $this->refresh();

        return $this;

    }

    /*

    |--------------------------------------------------------------------------

    | CONSULTATION

    |--------------------------------------------------------------------------

    */

    /** @return BelongsTo<Consultation, $this> */
    public function consultation(): BelongsTo
    {

        return $this->belongsTo(

            Consultation::class,

            'consultation_id'

        );

    }

    /*

    |--------------------------------------------------------------------------

    | REVIEWED BY

    |--------------------------------------------------------------------------

    */

    /** @return BelongsTo<User, $this> */
    public function reviewedBy(): BelongsTo
    {

        return $this->belongsTo(

            User::class,

            'reviewed_by_user_id'

        );

    }

    /*

    |--------------------------------------------------------------------------

    | ENSURE INVOICE

    |--------------------------------------------------------------------------

    */

    public function ensureInvoice(): self
    {

        /*

        |--------------------------------------------------------------------------

        | INVOICE ALREADY EXISTS

        |--------------------------------------------------------------------------

        */

        if ($this->invoice_number) {

            return $this;

        }

        /*

        |--------------------------------------------------------------------------

        | GET DOCTOR

        |--------------------------------------------------------------------------

        */

        $doctor = $this->doctor()->first();

        if (! $doctor) {

            return $this;

        }

        /*

        |--------------------------------------------------------------------------

        | PATIENT TYPE

        |--------------------------------------------------------------------------

        */

        $patientType =

            $this->patient_type

            ?? $this->patient->patient_type

            ?? 'Local';

        /*

        |--------------------------------------------------------------------------

        | FOREIGN / LOCAL

        |--------------------------------------------------------------------------

        */

        $isForeign =

            strcasecmp(

                (string) $patientType,

                'Foreign'

            ) === 0;

        /*

        |--------------------------------------------------------------------------

        | CONSULTATION FEE

        |--------------------------------------------------------------------------

        */

        $fee = $isForeign

            ? (float) ($doctor->foreign_fee ?? 0)

            : (float) ($doctor->local_fee ?? 0);

        /*

        |--------------------------------------------------------------------------

        | CURRENCY

        |--------------------------------------------------------------------------

        */

        $currency = $isForeign

            ? 'USD'

            : 'LKR';

        /*

        |--------------------------------------------------------------------------

        | CREATE INVOICE

        |--------------------------------------------------------------------------

        */

        $this->update([

            'appointment_fee' => $fee,

            'invoice_currency' => $currency,

            'invoice_number' => 'INV-APPT-'.

                str_pad(

                    (string) $this->id,

                    6,

                    '0',

                    STR_PAD_LEFT

                ),

            'invoice_issued_at' => now(),

            'payment_status' => $this->payment_status

                    ?: 'unpaid',

        ]);

        $this->refresh();

        return $this;

    }

    /*

    |--------------------------------------------------------------------------

    | MARK AS PAID

    |--------------------------------------------------------------------------

    */

    public function markAsPaid(

        string $paymentMethod,

        ?string $paymentReference = null

    ): self {

        /*

        |--------------------------------------------------------------------------

        | ENSURE INVOICE EXISTS

        |--------------------------------------------------------------------------

        */

        $this->ensureInvoice();

        /*

        |--------------------------------------------------------------------------

        | RECEIPT NUMBER

        |--------------------------------------------------------------------------

        */

        $receiptNumber =

            $this->receipt_number

            ?: 'RCP-APPT-'.

            str_pad(

                (string) $this->id,

                6,

                '0',

                STR_PAD_LEFT

            ).

            '-'.

            now()->format(

                'YmdHis'

            );

        /*

        |--------------------------------------------------------------------------

        | UPDATE PAYMENT

        |--------------------------------------------------------------------------

        */

        $this->update([

            'payment_status' => 'paid',

            'payment_method' => $paymentMethod,

            'payment_reference' => $paymentReference,

            'paid_at' => now(),

            'receipt_number' => $receiptNumber,

        ]);

        $this->refresh();

        return $this;

    }
}
