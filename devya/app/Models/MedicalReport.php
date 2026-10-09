<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MedicalReport extends Model
{
    /**
     * Allow all fields to be mass assigned.
     */
    protected $guarded = [];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'report_date' => 'date',
        'file_size' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | PATIENT
    |--------------------------------------------------------------------------
    */

    public function patient(): BelongsTo
    {
        return $this->belongsTo(
            Patient::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CONSULTATION
    |--------------------------------------------------------------------------
    */

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(
            Consultation::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOADED BY
    |--------------------------------------------------------------------------
    */

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by_user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FILE URL
    |--------------------------------------------------------------------------
    */

    public function getFileUrlAttribute(): ?string
    {
        if (empty($this->file_path)) {
            return null;
        }

        return Storage::disk('confidential')->temporaryUrl($this->file_path, now()->addMinutes(30));
    }

    /*
    |--------------------------------------------------------------------------
    | FORMATTED FILE SIZE
    |--------------------------------------------------------------------------
    */

    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = (int) ($this->file_size ?? 0);

        if ($bytes <= 0) {
            return '-';
        }

        if ($bytes < 1024) {
            return $bytes.' B';
        }

        if ($bytes < 1024 * 1024) {
            return number_format(
                $bytes / 1024,
                1
            ).' KB';
        }

        if ($bytes < 1024 * 1024 * 1024) {
            return number_format(
                $bytes / (1024 * 1024),
                1
            ).' MB';
        }

        return number_format(
            $bytes / (1024 * 1024 * 1024),
            1
        ).' GB';
    }
}
