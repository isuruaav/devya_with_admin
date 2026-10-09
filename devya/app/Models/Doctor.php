<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [

        'name',

        'nic_number',

        'doctor_photo',

        'nic_copy',

        'reg_no',

        'slmc_registration_document',

        'specialization',

        'qualification',

        'phone_number',

        'whatsapp_number',

        'email',

        'room_number',

        'max_patients_per_day',

        'local_fee',

        'foreign_fee',

        'is_active',

        'availability_status',

        // Old fields kept for backward compatibility

        'available_days',

        'schedule_start',

        'schedule_end',

        'slmc_document_expiry',

        'nic_document_expiry',

        'created_by_user_id',

        'updated_by_user_id',

        'deleted_by_user_id',

    ];

    protected function casts(): array
    {

        return [

            'available_days' => 'array',

            'slmc_document_expiry' => 'date',

            'nic_document_expiry' => 'date',

            'is_active' => 'boolean',

            'local_fee' => 'decimal:2',

            'foreign_fee' => 'decimal:2',

        ];

    }

    protected static function booted(): void
    {

        static::created(

            fn (Doctor $doctor): ?DoctorAudit => $doctor->recordAudit('created')

        );

        static::updated(

            fn (Doctor $doctor): ?DoctorAudit => $doctor->recordAudit('updated')

        );

        static::deleted(

            fn (Doctor $doctor): ?DoctorAudit => $doctor->recordAudit('deleted')

        );

    }

    /*

    |--------------------------------------------------------------------------

    | Relationships

    |--------------------------------------------------------------------------

    */

    /** @return HasMany<Consultation, $this> */
    public function consultations(): HasMany
    {

        return $this->hasMany(

            Consultation::class

        );

    }

    /** @return HasMany<DoctorAudit, $this> */
    public function audits(): HasMany
    {

        return $this->hasMany(

            DoctorAudit::class

        );

    }

    /*

    |--------------------------------------------------------------------------

    | Date-specific schedules

    |--------------------------------------------------------------------------

    */

    /** @return HasMany<DoctorSchedule, $this> */
    public function schedules(): HasMany
    {

        return $this->hasMany(

            DoctorSchedule::class

        );

    }

    /*

    |--------------------------------------------------------------------------

    | Weekly schedules

    |--------------------------------------------------------------------------

    */

    /** @return HasMany<DoctorWeeklySchedule, $this> */
    public function weeklySchedules(): HasMany
    {

        return $this->hasMany(

            DoctorWeeklySchedule::class

        );

    }

    public function consultationFeeFor(string $patientType): float
    {

        return strcasecmp($patientType, 'foreign') === 0

            ? (float) $this->foreign_fee

            : (float) $this->local_fee;

    }

    /*

    |--------------------------------------------------------------------------

    | Audit

    |--------------------------------------------------------------------------

    */

    public function recordAudit(

        string $event

    ): ?DoctorAudit {

        if (! $this->exists) {

            return null;

        }

        return $this->audits()->create([

            'user_id' => auth()->id(),

            'event' => $event,

            'old_values' => $event === 'created'

                    ? null

                    : $this->getRawOriginal(),

            'new_values' => $event === 'deleted'

                    ? null

                    : $this->getAttributes(),

        ]);

    }

    /*

    |--------------------------------------------------------------------------

    | Doctors Available For Date

    |--------------------------------------------------------------------------

    |

    | Priority:

    |

    | 1. Doctor active + availability status

    | 2. Date-specific schedule

    | 3. Weekly schedule

    |

    */

    /**
     * @param  Builder<Doctor>  $query
     * @return Builder<Doctor>
     */
    public function scopeAvailableForDate(

        Builder $query,

        string $date

    ): Builder {

        $day = strtolower(

            Carbon::parse($date)->format('l')

        );

        return $query

            ->where(

                'is_active',

                true

            )

            ->where(

                'availability_status',

                'available'

            )

            ->where(

                function (Builder $available) use (

                    $date,

                    $day

                ): void {

                    /*

                    |--------------------------------------------------------------------------

                    | 1. Date-specific schedule

                    |--------------------------------------------------------------------------

                    |

                    | If a special schedule exists for this date,

                    | it overrides the weekly schedule.

                    |

                    */

                    $available->whereHas(

                        'schedules',

                        function (

                            Builder $schedule

                        ) use ($date): void {

                            $schedule

                                ->whereDate(

                                    'schedule_date',

                                    $date

                                )

                                ->where(

                                    'status',

                                    'available'

                                );

                        }

                    );

                    /*

                    |--------------------------------------------------------------------------

                    | 2. Weekly schedule

                    |--------------------------------------------------------------------------

                    |

                    | Only the selected day is checked.

                    |

                    | Example:

                    |

                    | Monday    06:00 - 12:00

                    | Tuesday   14:00 - 22:00

                    |

                    */

                    $available->orWhere(

                        function (

                            Builder $weekly

                        ) use (

                            $date,

                            $day

                        ): void {

                            $weekly

                                ->whereHas(

                                    'weeklySchedules',

                                    function (

                                        Builder $schedule

                                    ) use ($day): void {

                                        $schedule

                                            ->where(

                                                'day_of_week',

                                                $day

                                            )

                                            ->where(

                                                'is_available',

                                                true

                                            );

                                    }

                                )

                                ->whereDoesntHave(

                                    'schedules',

                                    function (

                                        Builder $schedule

                                    ) use ($date): void {

                                        $schedule->whereDate(

                                            'schedule_date',

                                            $date

                                        );

                                    }

                                );

                        }

                    );

                }

            );

    }

    /*

    |--------------------------------------------------------------------------

    | Special Schedule For Date

    |--------------------------------------------------------------------------

    */

    public function availabilityForDate(

        string $date

    ): ?DoctorSchedule {

        return $this->schedules()

            ->whereDate(

                'schedule_date',

                $date

            )

            ->first();

    }

    /*

    |--------------------------------------------------------------------------

    | Weekly Schedule For Day

    |--------------------------------------------------------------------------

    */

    public function weeklyScheduleForDay(

        string $day

    ): ?DoctorWeeklySchedule {

        return $this->weeklySchedules()

            ->where(

                'day_of_week',

                strtolower(trim($day))

            )

            ->first();

    }

    /*

    |--------------------------------------------------------------------------

    | Is Doctor Available At Date/Time

    |--------------------------------------------------------------------------

    */

    public function isAvailableAt(

        string $dateTime

    ): bool {

        $appointment =

            Carbon::parse(

                $dateTime

            );

        /*

        |--------------------------------------------------------------------------

        | Basic doctor status

        |--------------------------------------------------------------------------

        */

        if (! $this->is_active) {

            return false;

        }

        if (

            $this->availability_status !==

            'available'

        ) {

            return false;

        }

        /*

        |--------------------------------------------------------------------------

        | Selected date and day

        |--------------------------------------------------------------------------

        */

        $date =

            $appointment->toDateString();

        $day =

            strtolower(

                $appointment->format('l')

            );

        $time =

            $appointment->format('H:i:s');

        /*

        |--------------------------------------------------------------------------

        | 1. Date-specific schedule

        |--------------------------------------------------------------------------

        |

        | Special date schedule has priority over weekly schedule.

        |

        */

        $schedule =

            $this->availabilityForDate(

                $date

            );

        if ($schedule) {

            /*

            |----------------------------------------------------------------------

            | Date marked unavailable / leave

            |----------------------------------------------------------------------

            */

            if (

                $schedule->status !==

                'available'

            ) {

                return false;

            }

            /*

            |----------------------------------------------------------------------

            | Special start time

            |----------------------------------------------------------------------

            */

            if (

                $schedule->start_time

                &&

                $time <

                    $schedule->start_time

            ) {

                return false;

            }

            /*

            |----------------------------------------------------------------------

            | Special end time

            |----------------------------------------------------------------------

            */

            if (

                $schedule->end_time

                &&

                $time >

                    $schedule->end_time

            ) {

                return false;

            }

            return true;

        }

        /*

        |--------------------------------------------------------------------------

        | 2. Weekly Schedule

        |--------------------------------------------------------------------------

        */

        $weeklySchedule =

            $this->weeklyScheduleForDay(

                $day

            );

        /*

        |--------------------------------------------------------------------------

        | Day not available

        |--------------------------------------------------------------------------

        */

        if (

            ! $weeklySchedule

            ||

            ! $weeklySchedule->is_available

        ) {

            return false;

        }

        /*

        |--------------------------------------------------------------------------

        | Weekly start time

        |--------------------------------------------------------------------------

        */

        if (

            $weeklySchedule->start_time

            &&

            $time <

                $weeklySchedule->start_time

        ) {

            return false;

        }

        /*

        |--------------------------------------------------------------------------

        | Weekly end time

        |--------------------------------------------------------------------------

        */

        if (

            $weeklySchedule->end_time

            &&

            $time >

                $weeklySchedule->end_time

        ) {

            return false;

        }

        return true;

    }

    /*

    |--------------------------------------------------------------------------

    | Photo URL

    |--------------------------------------------------------------------------

    */

    public function getPhotoUrlAttribute(): ?string
    {

        if (! $this->doctor_photo) {

            return null;

        }

        return str_starts_with(

            $this->doctor_photo,

            'http'

        )

            ? $this->doctor_photo

            : Storage::url(

                $this->doctor_photo

            );

    }
}
