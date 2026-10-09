<?php

namespace Tests\Feature;

use App\Models\ReceptionBill;
use App\Models\ReceptionCatalogItem;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use Tests\TestCase;

class ReceptionCatalogBillingTest extends TestCase
{
    protected function tearDown(): void
    {
        Schema::dropIfExists('reception_catalog_items');

        parent::tearDown();
    }

    public function test_bill_lines_use_the_active_catalog_name_and_currency_price(): void
    {
        $this->createCatalogTable();
        $catalogItem = $this->createCatalogItem();

        $totals = ReceptionBill::calculateCatalogAmounts([
            [
                'catalog_item_id' => $catalogItem->getKey(),
                'category' => 'retail_item',
                'description' => 'Forged item name',
                'quantity' => 2,
                'unit_price' => 0.01,
            ],
        ], 'LKR');

        $this->assertSame([
            'category' => 'beverage',
            'description' => 'Koththamalli',
            'quantity' => 2.0,
            'unit_price' => 225.5,
            'total' => 451.0,
        ], $totals['items'][0]);
        $this->assertSame(451.0, $totals['grand_total']);

        $foreignTotals = ReceptionBill::calculateCatalogAmounts([
            [
                'catalog_item_id' => $catalogItem->getKey(),
                'quantity' => 2,
            ],
        ], 'USD');

        $this->assertSame(3.0, $foreignTotals['grand_total']);
    }

    public function test_inactive_catalog_items_cannot_be_added_to_new_bills(): void
    {
        $this->createCatalogTable();
        $catalogItem = $this->createCatalogItem([
            'is_active' => false,
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Select an active catalog item for every bill line.');

        ReceptionBill::calculateCatalogAmounts([
            [
                'catalog_item_id' => $catalogItem->getKey(),
                'quantity' => 1,
            ],
        ], 'LKR');
    }

    public function test_soft_deleted_catalog_items_cannot_be_added_to_new_bills(): void
    {
        $this->createCatalogTable();
        $catalogItem = $this->createCatalogItem();
        $catalogItem->delete();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Select an active catalog item for every bill line.');

        ReceptionBill::calculateCatalogAmounts([
            [
                'catalog_item_id' => $catalogItem->getKey(),
                'quantity' => 1,
            ],
        ], 'LKR');
    }

    public function test_restored_catalog_items_can_be_added_to_new_bills(): void
    {
        $this->createCatalogTable();
        $catalogItem = $this->createCatalogItem();
        $catalogItem->delete();

        $this->assertDatabaseHas('reception_catalog_items', [
            'id' => $catalogItem->getKey(),
        ]);
        $this->assertNotNull($catalogItem->deleted_at);

        $catalogItem->restore();

        $this->assertNull($catalogItem->fresh()->deleted_at);
        $this->assertSame(225.5, ReceptionBill::calculateCatalogAmounts([
            [
                'catalog_item_id' => $catalogItem->getKey(),
                'quantity' => 1,
            ],
        ], 'LKR')['grand_total']);
    }

    public function test_catalog_billing_rejects_unsupported_currencies(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Reception bill currency must be LKR or USD.');

        ReceptionBill::calculateCatalogAmounts([], 'EUR');
    }

    private function createCatalogTable(): void
    {
        Schema::create('reception_catalog_items', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('category', 50);
            $table->decimal('unit_price_lkr', 12, 2);
            $table->decimal('unit_price_usd', 12, 2);
            $table->boolean('is_active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createCatalogItem(array $attributes = []): ReceptionCatalogItem
    {
        return ReceptionCatalogItem::query()->create(array_merge([
            'name' => 'Koththamalli',
            'category' => 'beverage',
            'unit_price_lkr' => 225.50,
            'unit_price_usd' => 1.50,
            'is_active' => true,
        ], $attributes));
    }
}
