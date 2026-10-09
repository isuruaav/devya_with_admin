<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Consultation extends Model
{
    protected $guarded = [];

    /*

    |--------------------------------------------------------------------------

    | CASTS

    |--------------------------------------------------------------------------

    */

    protected $casts = [

        'consultation_date' => 'date',

        'doctor_fee' => 'decimal:2',

    ];

    /*

    |--------------------------------------------------------------------------

    | BOOTED

    |--------------------------------------------------------------------------

    */

    protected static function booted(): void
    {

        /*

        |--------------------------------------------------------------------------

        | CREATE QUEUE NUMBER

        |--------------------------------------------------------------------------

        */

        static::creating(function (Consultation $consultation): void {

            if ($consultation->queue_number !== null) {

                return;

            }

            $date = $consultation->consultation_date

                ?? now()->toDateString();

            $lastQueueNumber = static::query()

                ->where(

                    'doctor_id',

                    $consultation->doctor_id

                )

                ->whereDate(

                    'consultation_date',

                    $date

                )

                ->max('queue_number');

            $consultation->queue_number =

                ((int) $lastQueueNumber) + 1;

        });

        /*

        |--------------------------------------------------------------------------

        | CREATE CONSULTATION NUMBER

        |--------------------------------------------------------------------------

        */

        static::created(function (Consultation $consultation): void {

            if (

                blank(

                    $consultation->consultation_number

                )

            ) {

                $consultation->updateQuietly([

                    'consultation_number' => 'CONS-'.

                        str_pad(

                            (string) $consultation->id,

                            6,

                            '0',

                            STR_PAD_LEFT

                        ),

                ]);

            }

        });

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

        OnlineAppointment::query()

            ->where(

                'patient_id',

                $this->patient_id

            )

            ->where(

                'consultation_id',

                $this->getKey()

            )

            ->update([

                'doctor_id' => $doctor->getKey(),

            ]);

        $this->refresh();

        return $this;

    }

    /*

    |--------------------------------------------------------------------------

    | TREATMENTS

    |--------------------------------------------------------------------------

    */

    /** @return HasMany<ConsultationTreatment, $this> */
    public function treatments(): HasMany
    {

        return $this->hasMany(

            ConsultationTreatment::class

        );

    }

    /*

    |--------------------------------------------------------------------------

    | MEDICINES

    |--------------------------------------------------------------------------

    |

    | These are prescription records.

    |

    | IMPORTANT:

    |

    | Consultation එකේදී medicine stock deduct කරන්නේ නැහැ.

    |

    | Pharmacy payment complete වුණාට පස්සේ

    | PharmacyBill::markAsPaid() මගින් stock deduct වෙනවා.

    |

    */

    /** @return HasMany<ConsultationMedicine, $this> */
    public function medicines(): HasMany
    {

        return $this->hasMany(

            ConsultationMedicine::class

        );

    }

    /*

    |--------------------------------------------------------------------------

    | CONSULTATION BILL

    |--------------------------------------------------------------------------

    */

    /** @return HasOne<ConsultationBill, $this> */
    public function bill(): HasOne
    {

        return $this->hasOne(

            ConsultationBill::class

        );

    }

    /*

    |--------------------------------------------------------------------------

    | CREATE / UPDATE CONSULTATION BILL

    |--------------------------------------------------------------------------

    |

    | CONSULTATION BILL:

    |

    | Doctor Fee

    | + Treatment Total

    | = Gross Total

    |

    | Medicine price IS NOT included in Gross Total.

    |

    | Medicine records remain available for:

    |

    | 1. Prescription

    | 2. Consultation Bill medicine list

    | 3. Pharmacy billing

    |

    | Appointment / Channeling payment is deducted from

    | the Consultation Bill gross total.

    |

    */

    public function createBill(): ConsultationBill
    {

        /*

        |--------------------------------------------------------------------------

        | LOAD RELATIONSHIPS

        |--------------------------------------------------------------------------

        */

        $this->loadMissing([

            'patient',

            'doctor',

            'treatments',

            'medicines',

        ]);

        $totals = $this->calculateBillTotals();

        $doctorFee = $totals['doctor_fee'];

        $treatmentTotal = $totals['treatment_total'];

        $medicineTotal = $totals['medicine_total'];

        $grossTotal = $totals['grand_total'];

        /*

        |--------------------------------------------------------------------------

        | APPOINTMENT / CHANNELING FEE ALREADY PAID

        |--------------------------------------------------------------------------

        |

        | Appointment fee is already paid during appointment/channeling.

        |

        | Therefore it is deducted from the Consultation Bill.

        |

        */

        $appointmentPaid = 0;

        /*

        |--------------------------------------------------------------------------

        | FIND THE APPOINTMENT

        |--------------------------------------------------------------------------

        |

        | CreateConsultation workflow එකේදී appointment එකට

        | consultation_id save කරන නිසා ඒකෙන් direct appointment

        | එක හොයාගන්නවා.

        |

        | මෙය patient/date search එකට වඩා reliable.

        |

        */

        $appointment = OnlineAppointment::query()

            ->where(

                'consultation_id',

                $this->id

            )

            ->latest('id')

            ->first();

        /*

        |--------------------------------------------------------------------------

        | FALLBACK APPOINTMENT SEARCH

        |--------------------------------------------------------------------------

        |

        | Existing old records වල consultation_id නොතිබුණොත්

        | patient + consultation date මගින් appointment එක හොයනවා.

        |

        */

        if (! $appointment) {

            $appointment = OnlineAppointment::query()

                ->where(

                    'patient_id',

                    $this->patient_id

                )

                ->whereDate(

                    'appointment_date',

                    $this->consultation_date

                        ?? now()->toDateString()

                )

                ->latest('id')

                ->first();

        }

        /*

        |--------------------------------------------------------------------------

        | CHECK APPOINTMENT PAYMENT

        |--------------------------------------------------------------------------

        */

        if ($appointment) {

            $paymentStatus = strtolower(

                (string) (

                    $appointment->payment_status

                    ?? ''

                )

            );

            /*

            |--------------------------------------------------------------------------

            | PAID APPOINTMENT

            |--------------------------------------------------------------------------

            */

            if (

                in_array(

                    $paymentStatus,

                    [

                        'paid',

                        'completed',

                        'success',

                    ],

                    true

                )

            ) {

                /*

                |--------------------------------------------------------------------------

                | GET PAID AMOUNT

                |--------------------------------------------------------------------------

                */

                if (

                    isset(

                        $appointment->doctor_fee

                    )

                    &&

                    (float) $appointment->doctor_fee > 0

                ) {

                    $appointmentPaid =

                        (float)

                        $appointment->doctor_fee;

                } elseif (

                    isset(

                        $appointment->channeling_fee

                    )

                    &&

                    (float) $appointment->channeling_fee > 0

                ) {

                    $appointmentPaid =

                        (float)

                        $appointment->channeling_fee;

                } elseif (

                    isset(

                        $appointment->fee

                    )

                    &&

                    (float) $appointment->fee > 0

                ) {

                    $appointmentPaid =

                        (float)

                        $appointment->fee;

                } elseif (

                    isset(

                        $appointment->amount_paid

                    )

                    &&

                    (float) $appointment->amount_paid > 0

                ) {

                    $appointmentPaid =

                        (float)

                        $appointment->amount_paid;

                } elseif (

                    isset(

                        $appointment->paid_amount

                    )

                    &&

                    (float) $appointment->paid_amount > 0

                ) {

                    $appointmentPaid =

                        (float)

                        $appointment->paid_amount;

                } else {

                    /*

                    |--------------------------------------------------------------------------

                    | If appointment is marked paid but exact

                    | amount is unavailable, use doctor fee.

                    |--------------------------------------------------------------------------

                    */

                    $appointmentPaid =

                        $doctorFee;

                }

            }

        }

        /*

        |--------------------------------------------------------------------------

        | DO NOT DEDUCT MORE THAN CONSULTATION DOCTOR FEE

        |--------------------------------------------------------------------------

        |

        | Channeling payment should not make the consultation

        | bill negative.

        |

        */

        $appointmentPaid = min(

            $appointmentPaid,

            $doctorFee

        );

        /*

        |--------------------------------------------------------------------------

        | BALANCE DUE

        |--------------------------------------------------------------------------

        */

        $balanceDue = max(

            0,

            $grossTotal - $appointmentPaid

        );

        /*

        |--------------------------------------------------------------------------

        | CURRENCY

        |--------------------------------------------------------------------------

        */

        $currency = 'LKR';

        if ($this->patient) {

            $patientType = strtolower(

                (string) (

                    $this->patient->patient_type

                    ?? ''

                )

            );

            if (

                $patientType === 'foreign'

            ) {

                $currency = 'USD';

            }

        }

        /*

        |--------------------------------------------------------------------------

        | FIND EXISTING BILL

        |--------------------------------------------------------------------------

        */

        $bill = $this->bill;

        /*

        |--------------------------------------------------------------------------

        | CREATE BILL

        |--------------------------------------------------------------------------

        */

        if (! $bill) {

            $bill = ConsultationBill::create([

                'consultation_id' => $this->id,

                'bill_number' => 'CONS-BILL-'.str_pad(

                    (string) $this->id,

                    6,

                    '0',

                    STR_PAD_LEFT

                ),

                'currency' => $currency,

                'doctor_fee' => $doctorFee,

                'treatment_total' => $treatmentTotal,

                'medicine_total' => $medicineTotal,

                'appointment_paid' => $appointmentPaid,

                'grand_total' => $grossTotal,

                'balance_due' => $balanceDue,

                'payment_status' => $balanceDue > 0

                        ? 'unpaid'

                        : 'paid',

            ]);

        } else {

            /*

            |--------------------------------------------------------------------------

            | DO NOT MODIFY A COMPLETED BILL

            |--------------------------------------------------------------------------

            |

            | Once reception has completed the payment,

            | don't overwrite the financial values.

            |

            */

            if (

                $bill->payment_status !== 'paid'

            ) {

                $bill->update([

                    'currency' => $currency,

                    'doctor_fee' => $doctorFee,

                    'treatment_total' => $treatmentTotal,

                    'medicine_total' => $medicineTotal,

                    'appointment_paid' => $appointmentPaid,

                    'grand_total' => $grossTotal,

                    'balance_due' => $balanceDue,

                ]);

            }

        }

        /*

        |--------------------------------------------------------------------------

        | RETURN BILL

        |--------------------------------------------------------------------------

        */

        $bill->refresh();
        $bill->load([
            'consultation.patient',
            'consultation.doctor',
        ]);

        return $bill;

    }

    /**
     * @return array{

     *     doctor_fee: float,

     *     treatment_total: float,

     *     medicine_total: float,

     *     grand_total: float

     * }
     */
    public function calculateBillTotals(): array
    {

        $this->loadMissing([

            'treatments',

            'medicines',

        ]);

        $doctorFee = (float) ($this->doctor_fee ?? 0);

        $treatmentTotal = 0.0;

        foreach ($this->treatments as $treatment) {

            $price = (float) ($treatment->price ?? 0);

            $quantity = (float) ($treatment->quantity ?? 1);

            $treatmentTotal += $price * ($quantity > 0 ? $quantity : 1);

        }

        $medicineTotal = 0.0;

        foreach ($this->medicines as $medicineItem) {

            $quantity = (float) ($medicineItem->quantity ?? 0);

            $price = (float) ($medicineItem->unit_price ?? $medicineItem->price ?? 0);

            if ($quantity > 0) {

                $medicineTotal += $quantity * $price;

            }

        }

        return [

            'doctor_fee' => $doctorFee,

            'treatment_total' => $treatmentTotal,

            'medicine_total' => $medicineTotal,

            'grand_total' => $doctorFee + $treatmentTotal,

        ];

    }
}
