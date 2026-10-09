<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffAttendanceRequest extends Model
{
    use HasFactory;

    protected $table = 'staff_attendance_requests';

    protected $fillable = [
        'staff_id',
        'attendance_date',
        'requested_at',
        'attendance_type',
        'method',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'device_ip',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'requested_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(
            Staff::class,
            'staff_id'
        );
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}
