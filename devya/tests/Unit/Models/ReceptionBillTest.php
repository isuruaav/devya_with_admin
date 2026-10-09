<?php

namespace Tests\Unit\Models;

use App\Models\ReceptionBill;
use App\Models\ReceptionBillItem;
use InvalidArgumentException;
use Tests\TestCase;

class ReceptionBillTest extends TestCase
{
    public function test_it_totals_manual_services_and_sale_items_after_discount(): void
    {
        $totals = ReceptionBill::calculateAmounts([
            [
                'category' => 'beverage',
                'description' => 'Koththamalli',
                'quantity' => 2,
                'unit_price' => 250,
            ],
            [
                'category' => 'prepared_remedy',
                'description' => 'Herbal preparation',
                'quantity' => 1,
                'unit_price' => 1800,
            ],
        ], 100);

        $this->assertSame(2300.0, $totals['subtotal']);
        $this->assertSame(100.0, $totals['discount']);
        $this->assertSame(2200.0, $totals['grand_total']);
        $this->assertSame(500.0, $totals['items'][0]['total']);
        $this->assertSame(1800.0, $totals['items'][1]['total']);
    }

    public function test_it_rejects_a_discount_greater_than_the_subtotal(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Discount must be between zero and the bill subtotal.');

        ReceptionBill::calculateAmounts([
            [
                'description' => 'Coffee',
                'quantity' => 1,
                'unit_price' => 250,
            ],
        ], 251);
    }

    public function test_it_rejects_items_with_nonpositive_quantities(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Each item needs a valid category, description, positive quantity, and price.');

        ReceptionBill::calculateAmounts([
            [
                'description' => 'Tea',
                'quantity' => 0,
                'unit_price' => 100,
            ],
        ]);
    }

    public function test_it_rejects_item_categories_outside_the_reception_sales_catalog(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Each item needs a valid category, description, positive quantity, and price.');

        ReceptionBill::calculateAmounts([
            [
                'category' => 'medicine_stock',
                'description' => 'Medicine',
                'quantity' => 1,
                'unit_price' => 100,
            ],
        ]);
    }

    public function test_it_calculates_change_when_the_customer_pays_more_than_the_balance(): void
    {
        $settlement = ReceptionBill::calculateSettlement(2200, 2500);

        $this->assertSame([
            'amount_received' => 2500.0,
            'change_amount' => 300.0,
            'balance_due' => 0.0,
        ], $settlement);
    }

    public function test_it_rejects_payment_below_the_outstanding_balance(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Received amount is less than the outstanding balance.');

        ReceptionBill::calculateSettlement(2200, 2199.99);
    }

    public function test_reception_bill_print_view_shows_manual_line_items_and_print_action(): void
    {
        $bill = new ReceptionBill([
            'bill_number' => 'REC-BILL-000001',
            'currency' => 'LKR',
            'subtotal' => 750,
            'grand_total' => 750,
            'balance_due' => 750,
            'payment_status' => 'unpaid',
        ]);
        $bill->setRelation('items', collect([
            new ReceptionBillItem([
                'category' => 'beverage',
                'description' => 'Koththamalli',
                'quantity' => 3,
                'unit_price' => 250,
                'total' => 750,
            ]),
        ]));

        $html = view('receipts.reception-bill-80mm', [
            'bill' => $bill,
        ])->render();

        $this->assertStringContainsString('REC-BILL-000001', $html);
        $this->assertStringContainsString('Koththamalli', $html);
        $this->assertStringContainsString('Beverage', $html);
        $this->assertStringContainsString('window.print();', $html);
    }

    public function test_reception_bill_print_view_supports_walk_in_sales_without_customer_details(): void
    {
        $bill = new ReceptionBill([
            'bill_number' => 'REC-BILL-000002',
            'currency' => 'LKR',
            'subtotal' => 250,
            'grand_total' => 250,
            'balance_due' => 250,
            'payment_status' => 'unpaid',
        ]);
        $bill->setRelation('items', collect([
            new ReceptionBillItem([
                'category' => 'beverage',
                'description' => 'Koththamalli',
                'quantity' => 1,
                'unit_price' => 250,
                'total' => 250,
            ]),
        ]));

        $html = view('receipts.reception-bill-80mm', [
            'bill' => $bill,
        ])->render();

        $this->assertStringContainsString('Walk-in sale', $html);
        $this->assertStringNotContainsString('Walk-in Customer Name', $html);
    }

    public function test_reception_bill_a4_print_view_includes_item_and_settlement_details(): void
    {
        $bill = new ReceptionBill([
            'bill_number' => 'REC-BILL-000003',
            'currency' => 'LKR',
            'subtotal' => 750,
            'discount' => 50,
            'grand_total' => 700,
            'amount_received' => 800,
            'change_amount' => 100,
            'balance_due' => 0,
            'payment_status' => 'paid',
        ]);
        $bill->setRelation('items', collect([
            new ReceptionBillItem([
                'category' => 'beverage',
                'description' => 'Koththamalli',
                'quantity' => 3,
                'unit_price' => 250,
                'total' => 750,
            ]),
        ]));

        $html = view('receipts.reception-bill-a4', [
            'bill' => $bill,
        ])->render();

        $this->assertStringContainsString('size: A4 portrait', $html);
        $this->assertStringContainsString('REC-BILL-000003', $html);
        $this->assertStringContainsString('Koththamalli', $html);
        $this->assertStringContainsString('LKR 700.00', $html);
        $this->assertStringContainsString('LKR 100.00', $html);
        $this->assertStringContainsString('window.print();', $html);
    }
}
