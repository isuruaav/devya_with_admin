<?php

namespace Tests\Feature;

use App\Filament\Resources\ReceptionBills\Tables\ReceptionBillsTable;
use App\Models\ReceptionBill;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReceptionBillsTableTest extends TestCase
{
    protected function tearDown(): void
    {
        Schema::dropIfExists('reception_bills');

        parent::tearDown();
    }

    public function test_bill_date_filter_includes_bills_on_both_selected_dates(): void
    {
        $this->createReceptionBillsTable();
        $beforeRange = $this->createReceptionBill('REC-BILL-000001', '2026-09-30 23:59:59');
        $startDate = $this->createReceptionBill('REC-BILL-000002', '2026-10-01 00:00:01');
        $endDate = $this->createReceptionBill('REC-BILL-000003', '2026-10-03 23:59:59');
        $afterRange = $this->createReceptionBill('REC-BILL-000004', '2026-10-04 00:00:00');

        $bills = ReceptionBillsTable::applyDateRange(
            ReceptionBill::query(),
            [
                'from' => '2026-10-01',
                'until' => '2026-10-03',
            ]
        )->get();

        $this->assertSame(
            [$startDate->getKey(), $endDate->getKey()],
            $bills->modelKeys()
        );
        $this->assertNotContains($beforeRange->getKey(), $bills->modelKeys());
        $this->assertNotContains($afterRange->getKey(), $bills->modelKeys());
    }

    private function createReceptionBillsTable(): void
    {
        Schema::create('reception_bills', function (Blueprint $table): void {
            $table->id();
            $table->string('bill_number');
            $table->timestamps();
        });
    }

    private function createReceptionBill(string $billNumber, string $createdAt): ReceptionBill
    {
        return ReceptionBill::query()->create([
            'bill_number' => $billNumber,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }
}
