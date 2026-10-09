<?php

namespace Tests\Feature;

use App\Filament\Pages\MyStock;
use App\Filament\Resources\Medicines\MedicineResource;
use App\Filament\Resources\PharmacyBills\PharmacyBillResource;
use App\Filament\Resources\ReceptionBills\ReceptionBillResource;
use App\Http\Controllers\PharmacyBillPrintController;
use App\Http\Controllers\PharmacyBillReceiptController;
use App\Models\PharmacyBill;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\Concerns\InteractsWithPermissions;
use Tests\TestCase;

class PharmacyRoleAccessTest extends TestCase
{
    use InteractsWithPermissions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpPermissions();
    }

    public function test_pharmacy_user_can_access_pharmacy_bills_medicines_and_stock_only(): void
    {
        $this->actingAs($this->userWithRole('pharmacy'));

        $this->assertTrue(PharmacyBillResource::canAccess());
        $this->assertTrue(MedicineResource::canAccess());
        $this->assertTrue(MyStock::canAccess());
        $this->assertFalse(ReceptionBillResource::canAccess());
    }

    public function test_reception_user_is_forbidden_from_printing_pharmacy_bills(): void
    {
        $this->actingAs($this->userWithRole('reception'));

        $this->assertThrows(
            fn () => app(PharmacyBillPrintController::class)->print(new PharmacyBill([
                'payment_status' => 'paid',
            ])),
            fn (HttpException $exception): bool => $exception->getStatusCode() === 403
        );
    }

    public function test_reception_user_is_forbidden_from_printing_pharmacy_receipts(): void
    {
        $this->actingAs($this->userWithRole('reception'));

        $this->assertThrows(
            fn () => app(PharmacyBillReceiptController::class)->show(new PharmacyBill([
                'payment_status' => 'paid',
            ])),
            fn (HttpException $exception): bool => $exception->getStatusCode() === 403
        );
    }
}
