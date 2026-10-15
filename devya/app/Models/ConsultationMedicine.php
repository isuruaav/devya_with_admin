<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationMedicine extends Model
{
    protected $guarded = [];

    protected $casts = [
        'breakfast_dose' => 'integer',
        'lunch_dose' => 'integer',
        'dinner_dose' => 'integer',
        'duration_days' => 'integer',
        'quantity' => 'integer',
    ];

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

    /**
     * Calculate the prescribed units without modifying pharmacy stock.
     */
    public static function calculateQuantity(
        int $breakfastDose,
        int $lunchDose,
        int $dinnerDose,
        int $durationDays,
    ): int {
        return (max(0, $breakfastDose) + max(0, $lunchDose) + max(0, $dinnerDose))
            * max(1, $durationDays);
    }

    /**
     * Supports structured meal dosing and existing free-text prescriptions.
     */
    public function getPrescriptionInstructionsAttribute(): string
    {
        $instructions = [];

        foreach ([
            'breakfast' => 'Breakfast',
            'lunch' => 'Lunch',
            'dinner' => 'Dinner',
        ] as $meal => $label) {
            $dose = (int) $this->getAttribute("{$meal}_dose");
            if ($dose < 1) {
                continue;
            }

            $timing = $this->getAttribute("{$meal}_timing");
            $prefix = in_array($timing, ['before', 'after'], true)
                ? ucfirst($timing).' '
                : '';

            $instructions[] = "{$prefix}{$label}: {$dose}";
        }

        if ($instructions === []) {
            return (string) ($this->dosage ?: 'As directed by doctor');
        }

        $durationDays = max(1, (int) $this->duration_days);
        $instructions[] = 'For '.$durationDays.' '.($durationDays === 1 ? 'day' : 'days');

        return implode('; ', $instructions);
    }

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
