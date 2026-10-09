<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorWeeklySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'is_available',
        'start_time',
        'end_time',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Doctor
    |--------------------------------------------------------------------------
    */

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(
            Doctor::class
        );
    }
}
