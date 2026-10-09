<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationBill extends Model
{
    protected $guarded = [];

    protected $casts = [
        'doctor_fee' => 'decimal:2',
        'treatment_total' => 'decimal:2',
        'medicine_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'appointment_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',

        // Payment amounts
        'amount_received' => 'decimal:2',
        'change_amount' => 'decimal:2',

        'paid_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Consultation
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
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by_user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Paid By
    |--------------------------------------------------------------------------
    */

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'paid_by_user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Mark Bill As Paid
    |--------------------------------------------------------------------------
    |
    | Amount received from customer is stored.
    | If customer gives more than the balance,
    | the difference is stored as change.
    |
    */

    public function markAsPaid(
        string $paymentMethod,
        float $amountReceived,
        ?string $paymentReference = null
    ): self {

        $balanceDue = max(
            0,
            (float) $this->balance_due
        );

        $amountReceived = max(
            0,
            $amountReceived
        );

        /*
        |--------------------------------------------------------------------------
        | Validate payment amount
        |--------------------------------------------------------------------------
        |
        | Customer must pay at least the outstanding balance.
        |
        */

        if ($amountReceived < $balanceDue) {
            throw new \InvalidArgumentException(
                'Received amount is less than the outstanding balance.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate change
        |--------------------------------------------------------------------------
        */

        $changeAmount = max(
            0,
            $amountReceived - $balanceDue
        );

        /*
        |--------------------------------------------------------------------------
        | Generate receipt number
        |--------------------------------------------------------------------------
        */

        $receiptNumber = $this->receipt_number;

        if (! $receiptNumber) {
            $receiptNumber =
                'RCP-CONS-'.
                str_pad(
                    (string) $this->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                ).
                '-'.
                now()->format('YmdHis');
        }

        /*
        |--------------------------------------------------------------------------
        | Update payment
        |--------------------------------------------------------------------------
        */

        $this->update([
            'payment_status' => 'paid',

            'payment_method' => $paymentMethod,

            'payment_reference' => $paymentReference,

            'amount_received' => $amountReceived,

            'change_amount' => $changeAmount,

            'balance_due' => 0,

            'paid_at' => now(),

            'receipt_number' => $receiptNumber,

            'paid_by_user_id' => auth()->id(),
        ]);

        return $this->fresh();
    }
}
