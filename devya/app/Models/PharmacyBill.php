<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class PharmacyBill extends Model
{
    protected $guarded = [];

    protected $casts = [

        'subtotal' => 'decimal:2',

        'discount' => 'decimal:2',

        'grand_total' => 'decimal:2',

        'amount_received' => 'decimal:2',

        'change_amount' => 'decimal:2',

        'balance_due' => 'decimal:2',

        'paid_at' => 'datetime',

    ];

    /*

    |--------------------------------------------------------------------------

    | PATIENT

    |--------------------------------------------------------------------------

    */

    /** @return BelongsTo<Patient, $this> */
    public function patient(): BelongsTo
    {

        return $this->belongsTo(

            Patient::class

        );

    }

    /*

    |--------------------------------------------------------------------------

    | ITEMS

    |--------------------------------------------------------------------------

    */

    /** @return HasMany<PharmacyBillItem, $this> */
    public function items(): HasMany
    {

        return $this->hasMany(

            PharmacyBillItem::class

        );

    }

    /*

    |--------------------------------------------------------------------------

    | CREATED BY

    |--------------------------------------------------------------------------

    */

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {

        return $this->belongsTo(

            User::class,

            'created_by_user_id'

        );

    }

    /*

    |--------------------------------------------------------------------------

    | PAID BY

    |--------------------------------------------------------------------------

    */

    /** @return BelongsTo<User, $this> */
    public function paidBy(): BelongsTo
    {

        return $this->belongsTo(

            User::class,

            'paid_by_user_id'

        );

    }

    /*

    |--------------------------------------------------------------------------

    | MARK AS PAID

    |--------------------------------------------------------------------------

    |

    | IMPORTANT:

    |

    | Stock is deducted ONLY here.

    |

    */

    public function markAsPaid(

        string $paymentMethod,

        float $amountReceived,

        ?string $paymentReference = null

    ): self {

        return DB::transaction(function () use (

            $paymentMethod,

            $amountReceived,

            $paymentReference

        ): self {

            /*

            |--------------------------------------------------------------------------

            | LOCK BILL

            |--------------------------------------------------------------------------

            */

            $bill = static::query()

                ->lockForUpdate()

                ->findOrFail($this->getKey());

            /*

            |--------------------------------------------------------------------------

            | PREVENT DOUBLE PAYMENT

            |--------------------------------------------------------------------------

            */

            if (

                $bill->payment_status === 'paid'

            ) {

                throw new \InvalidArgumentException(
                    'This pharmacy bill has already been paid.'

                );

            }

            /*

            |--------------------------------------------------------------------------

            | BALANCE

            |--------------------------------------------------------------------------

            */

            $balanceDue = max(

                0,

                (float) $bill->balance_due

            );

            $amountReceived = max(

                0,

                $amountReceived

            );

            if (

                $amountReceived < $balanceDue

            ) {

                throw new \InvalidArgumentException(
                    'Received amount is less than the outstanding balance.'

                );

            }

            /*

            |--------------------------------------------------------------------------

            | LOAD ITEMS

            |--------------------------------------------------------------------------

            */

            $items = $bill->items()

                ->get();

            /*

            |--------------------------------------------------------------------------

            | VALIDATE ALL ITEMS BEFORE CHANGING STOCK

            |--------------------------------------------------------------------------

            */

            foreach ($items as $item) {

                if (! $item->medicine_id) {

                    throw new \InvalidArgumentException(
                        'Medicine is missing for bill item #'.

                        $item->getKey()

                    );

                }

                $quantity = (float) (

                    $item->quantity ?? 0

                );

                if ($quantity <= 0) {

                    throw new \InvalidArgumentException(
                        'Invalid quantity for bill item #'.

                        $item->getKey()

                    );

                }

                /*

                |--------------------------------------------------------------------------

                | PREVENT DOUBLE STOCK ISSUE

                |--------------------------------------------------------------------------

                */

                $alreadyIssued = (float) (

                    $item->issued_quantity ?? 0

                );

                if ($alreadyIssued > 0) {

                    throw new \InvalidArgumentException(
                        'Stock has already been issued for medicine: '.

                        ($item->medicine_name ?? 'Medicine')

                    );

                }

                /*

                |--------------------------------------------------------------------------

                | LOCK MEDICINE

                |--------------------------------------------------------------------------

                */

                $medicine = Medicine::query()

                    ->lockForUpdate()

                    ->find(

                        $item->medicine_id

                    );

                if (! $medicine) {

                    throw new \InvalidArgumentException(
                        'Medicine not found for bill item #'.

                        $item->getKey()

                    );

                }

                /*

                |--------------------------------------------------------------------------

                | CHECK STOCK

                |--------------------------------------------------------------------------

                */

                $currentStock = (float) (

                    $medicine->stock_quantity ?? 0

                );

                if (

                    $currentStock < $quantity

                ) {

                    throw new \InvalidArgumentException(
                        'Insufficient stock for '.

                        $medicine->name.

                        '. Available: '.

                        $currentStock.

                        ', Required: '.

                        $quantity

                    );

                }

            }

            /*

            |--------------------------------------------------------------------------

            | CHANGE

            |--------------------------------------------------------------------------

            */

            $changeAmount = max(

                0,

                $amountReceived - $balanceDue

            );

            /*

            |--------------------------------------------------------------------------

            | RECEIPT NUMBER

            |--------------------------------------------------------------------------

            */

            $receiptNumber =

                $bill->receipt_number;

            if (! $receiptNumber) {

                $receiptNumber =

                    'RCP-PHARM-'.

                    str_pad(

                        (string) $bill->getKey(),

                        6,

                        '0',

                        STR_PAD_LEFT

                    ).

                    '-'.

                    now()->format('YmdHis');

            }

            /*

            |--------------------------------------------------------------------------

            | UPDATE PAYMENT

            |--------------------------------------------------------------------------

            */

            $bill->update([

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

            /*

            |--------------------------------------------------------------------------

            | DEDUCT STOCK

            |--------------------------------------------------------------------------

            */

            foreach ($items as $item) {

                $medicine = Medicine::query()

                    ->lockForUpdate()

                    ->findOrFail(

                        $item->medicine_id

                    );

                $quantity = (float) (

                    $item->quantity ?? 0

                );

                /*

                |--------------------------------------------------------------------------

                | DEDUCT

                |--------------------------------------------------------------------------

                */

                $medicine->stock_quantity =

                    (int) $medicine->stock_quantity

                    - (int) $quantity;

                $medicine->save();

                /*

                |--------------------------------------------------------------------------

                | MARK ITEM AS ISSUED

                |--------------------------------------------------------------------------

                */

                $item->update([

                    'issued_quantity' => $quantity,

                    'issued_at' => now(),

                    'issued_by_user_id' => auth()->id(),

                ]);

            }

            /*

            |--------------------------------------------------------------------------

            | RETURN FRESH BILL

            |--------------------------------------------------------------------------

            */

            $bill->refresh();
            $bill->load([
                'patient',
                'items.medicine',
                'paidBy',
                'createdBy',
            ]);

            return $bill;

        });

    }
}
