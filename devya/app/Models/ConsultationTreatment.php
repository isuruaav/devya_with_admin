<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationTreatment extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CREATED
        |--------------------------------------------------------------------------
        */

        static::created(function (ConsultationTreatment $item): void {
            $item->loadMissing('consultation');

            if ($item->consultation) {
                $item->consultation->createBill();
            }
        });

        /*
        |--------------------------------------------------------------------------
        | UPDATED
        |--------------------------------------------------------------------------
        */

        static::updated(function (ConsultationTreatment $item): void {
            $item->loadMissing('consultation');

            if ($item->consultation) {
                $item->consultation->createBill();
            }
        });

        /*
        |--------------------------------------------------------------------------
        | DELETED
        |--------------------------------------------------------------------------
        */

        static::deleted(function (ConsultationTreatment $item): void {
            $item->loadMissing('consultation');

            if ($item->consultation) {
                $item->consultation->createBill();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CONSULTATION
    |--------------------------------------------------------------------------
    */

    /**
     * @return BelongsTo<Consultation, $this>
     */
    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | TREATMENT
    |--------------------------------------------------------------------------
    */

    /**
     * @return BelongsTo<Treatment, $this>
     */
    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }
}
