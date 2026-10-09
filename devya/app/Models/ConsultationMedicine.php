<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationMedicine extends Model
{
    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | IMPORTANT
    |--------------------------------------------------------------------------
    |
    | Consultation medicine is ONLY a prescription / consultation record.
    |
    | DO NOT deduct medicine stock here.
    |
    | Stock will be deducted ONLY when:
    |
    | Consultation
    |      ↓
    | Pharmacy Bill
    |      ↓
    | Payment Received
    |      ↓
    | Stock Deducted
    |
    */

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(
            Consultation::class
        );
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(
            Medicine::class
        );
    }
}
