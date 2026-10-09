<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Staff extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'staff_code',
        'qr_token',
        'full_name',
        'name_with_initials',
        'nic_passport',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'designation',
        'department_id',
        'joining_date',
        'employment_type',
        'basic_salary',
        'allowance',
        'photo',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'joining_date' => 'date',
            'basic_salary' => 'decimal:2',
            'allowance' => 'decimal:2',
        ];
    }

    /**
     * Automatically generate a secure QR token
     * when a new staff member is created.
     */
    protected static function booted(): void
    {
        static::creating(function (Staff $staff): void {
            if (empty($staff->qr_token)) {
                $staff->qr_token = Str::random(64);
            }
        });
    }

    /**
     * Department
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(
            Department::class,
            'department_id',
            'id'
        );
    }

    /**
     * Staff Documents
     */
    public function documents(): HasMany
    {
        return $this->hasMany(
            StaffDocument::class,
            'staff_id'
        );
    }

    /**
     * Salary History
     */
    public function salaryHistories(): HasMany
    {
        return $this->hasMany(
            StaffSalaryHistory::class,
            'staff_id'
        )
            ->orderByDesc('effective_date')
            ->orderByDesc('id');
    }

    /**
     * Attendance Records
     *
     * Contains actual IN / OUT records.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(
            StaffAttendance::class,
            'staff_id'
        )
            ->orderByDesc('attendance_date')
            ->orderByDesc('check_in');
    }

    /**
     * Attendance Requests
     *
     * Used for NIC / Passport attendance requests.
     */
    public function attendanceRequests(): HasMany
    {
        return $this->hasMany(
            StaffAttendanceRequest::class,
            'staff_id'
        )
            ->orderByDesc('requested_at');
    }
}
