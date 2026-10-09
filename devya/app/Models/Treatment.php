<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treatment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'service_type',
        'code',
        'duration_minutes',
        'local_price',
        'foreign_price',
        'description',
        'is_active',
    ];
}
