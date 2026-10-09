<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;

class ReceptionCatalogItem extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'unit_price_lkr' => 'decimal:2',
        'unit_price_usd' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function unitPriceForCurrency(string $currency): float
    {
        return match ($currency) {
            'LKR' => (float) $this->unit_price_lkr,
            'USD' => (float) $this->unit_price_usd,
            default => throw new InvalidArgumentException(
                'Reception bill currency must be LKR or USD.'
            ),
        };
    }
}
