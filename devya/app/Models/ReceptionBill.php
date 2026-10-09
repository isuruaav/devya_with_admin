<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ReceptionBill extends Model
{
    public const CATEGORIES = [
        'ayurveda_service' => 'Ayurveda Service',
        'prepared_remedy' => 'Prepared Remedy',
        'beverage' => 'Beverage',
        'retail_item' => 'Retail Item',
        'other' => 'Other',
    ];

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

    /** @return BelongsTo<Patient, $this> */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /** @return HasMany<ReceptionBillItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(ReceptionBillItem::class);
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /** @return BelongsTo<User, $this> */
    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by_user_id');
    }

    /**
     * @param  array<int, mixed>  $items
     * @return array{
     *     items: array<int, array{category: string, description: string, quantity: float, unit_price: float, total: float}>,
     *     subtotal: float,
     *     discount: float,
     *     grand_total: float
     * }
     */
    public static function calculateAmounts(array $items, float $discount = 0): array
    {
        $normalizedItems = [];
        $subtotal = 0.0;

        foreach ($items as $item) {
            if (! is_array($item)) {
                throw new InvalidArgumentException(
                    'Each item must be entered as a bill line.'
                );
            }

            $description = trim((string) ($item['description'] ?? ''));
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $category = (string) ($item['category'] ?? 'other');

            if (
                $description === ''
                || $quantity <= 0
                || $unitPrice < 0
                || ! array_key_exists($category, self::CATEGORIES)
            ) {
                throw new InvalidArgumentException(
                    'Each item needs a valid category, description, positive quantity, and price.'
                );
            }

            $lineTotal = round($quantity * $unitPrice, 2);
            $subtotal += $lineTotal;

            $normalizedItems[] = [
                'category' => $category,
                'description' => $description,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => $lineTotal,
            ];
        }

        $subtotal = round($subtotal, 2);
        $discount = round($discount, 2);

        if ($discount < 0 || $discount > $subtotal) {
            throw new InvalidArgumentException(
                'Discount must be between zero and the bill subtotal.'
            );
        }

        return [
            'items' => $normalizedItems,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'grand_total' => round($subtotal - $discount, 2),
        ];
    }

    /**
     * @param  array<int|string, mixed>  $items
     * @return array{
     *     items: array<int, array{category: string, description: string, quantity: float, unit_price: float, total: float}>,
     *     subtotal: float,
     *     discount: float,
     *     grand_total: float
     * }
     */
    public static function calculateCatalogAmounts(
        array $items,
        string $currency,
        float $discount = 0
    ): array {
        if (! in_array($currency, ['LKR', 'USD'], true)) {
            throw new InvalidArgumentException(
                'Reception bill currency must be LKR or USD.'
            );
        }

        $catalogItemIds = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                throw new InvalidArgumentException(
                    'Select an active catalog item for every bill line.'
                );
            }

            $catalogItemId = filter_var(
                $item['catalog_item_id'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (! $catalogItemId) {
                throw new InvalidArgumentException(
                    'Select an active catalog item for every bill line.'
                );
            }

            $catalogItemIds[] = $catalogItemId;
        }

        $catalogItems = ReceptionCatalogItem::query()
            ->whereIn('id', array_unique($catalogItemIds))
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $normalizedItems = [];

        foreach ($items as $item) {
            $catalogItem = $catalogItems->get((int) ($item['catalog_item_id'] ?? 0));

            if (! $catalogItem) {
                throw new InvalidArgumentException(
                    'Select an active catalog item for every bill line.'
                );
            }

            $normalizedItems[] = [
                'category' => $catalogItem->category,
                'description' => $catalogItem->name,
                'quantity' => $item['quantity'] ?? 0,
                'unit_price' => $catalogItem->unitPriceForCurrency($currency),
            ];
        }

        return self::calculateAmounts($normalizedItems, $discount);
    }

    /**
     * @return array{amount_received: float, change_amount: float, balance_due: float}
     */
    public static function calculateSettlement(float $balanceDue, float $amountReceived): array
    {
        $balanceDue = max(0, $balanceDue);

        if ($amountReceived < $balanceDue) {
            throw new InvalidArgumentException(
                'Received amount is less than the outstanding balance.'
            );
        }

        return [
            'amount_received' => $amountReceived,
            'change_amount' => max(0, $amountReceived - $balanceDue),
            'balance_due' => 0.0,
        ];
    }

    public function markAsPaid(
        string $paymentMethod,
        float $amountReceived,
        ?string $paymentReference = null
    ): self {
        return DB::transaction(function () use ($paymentMethod, $amountReceived, $paymentReference): self {
            $bill = static::query()
                ->lockForUpdate()
                ->findOrFail($this->getKey());

            if (in_array($bill->payment_status, ['paid', 'cancelled'], true)) {
                throw new InvalidArgumentException('This reception bill cannot receive payment.');
            }

            $balanceDue = max(0, (float) $bill->balance_due);
            $settlement = self::calculateSettlement($balanceDue, $amountReceived);

            $bill->update([
                'payment_status' => 'paid',
                'payment_method' => $paymentMethod,
                'payment_reference' => $paymentReference,
                'amount_received' => $settlement['amount_received'],
                'change_amount' => $settlement['change_amount'],
                'balance_due' => $settlement['balance_due'],
                'paid_at' => now(),
                'receipt_number' => $bill->receipt_number
                    ?: 'RCP-SALE-'.str_pad((string) $bill->getKey(), 6, '0', STR_PAD_LEFT),
                'paid_by_user_id' => auth()->id(),
            ]);

            $bill->refresh();

            return $bill;
        });
    }
}
