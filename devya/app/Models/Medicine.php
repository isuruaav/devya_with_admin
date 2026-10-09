<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Medicine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'barcode',
        'category',
        'unit',
        'unit_price',
        'foreign_price',
        'cost_price',
        'stock_quantity',
        'reorder_level',
        'description',
        'is_active',
        'created_by_user_id',
        'updated_by_user_id',
        'deleted_by_user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'unit_price' => 'decimal:2',
        'foreign_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'reorder_level' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Medicine $medicine): void {
            if (blank($medicine->code)) {
                $medicine->code = 'MED-'.Str::upper(Str::random(8));
            }
        });
    }

    public function consultationMedicines(): HasMany
    {
        return $this->hasMany(ConsultationMedicine::class);
    }
}
