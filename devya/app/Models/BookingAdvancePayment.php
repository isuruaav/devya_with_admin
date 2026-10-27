<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingAdvancePayment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'additional_payhere_payment_ids' => 'array',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /** @return BelongsTo<OnlineAppointment, $this> */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(OnlineAppointment::class, 'online_appointment_id');
    }
}
