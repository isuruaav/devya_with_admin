<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffSalaryHistory extends Model
{
    use HasFactory;

    protected $table = 'staff_salary_histories';

    protected $fillable = [
        'staff_id',
        'basic_salary',
        'allowance',
        'effective_date',
        'reason',
        'changed_by',
    ];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:2',
            'allowance' => 'decimal:2',
            'effective_date' => 'date',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(
            Staff::class,
            'staff_id'
        );
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'changed_by'
        );
    }
}
