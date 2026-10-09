<?php

namespace Tests\Feature;

use App\Filament\Resources\Countries\CountryResource;
use App\Filament\Resources\Medicines\MedicineResource;
use App\Filament\Resources\Patients\PatientResource;
use App\Models\Country;
use App\Models\Medicine;
use App\Models\Patient;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\InteractsWithPermissions;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use InteractsWithPermissions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpPermissions();

        foreach ([
            '0001_01_00_000000_create_countries_table.php',
            '0001_01_01_000002_create_patients_table.php',
            '0001_01_01_000003_create_medicines_table.php',
        ] as $migration) {
            (require database_path('migrations/'.$migration))->up();
        }
    }

    public static function searchableResources(): array
    {
        return [
            'country' => [CountryResource::class, Country::class, ['name' => 'Sri Lanka', 'code' => 'LKA'], 'Sri', 'Sri Lanka', 'name'],
            'patient' => [PatientResource::class, Patient::class, ['full_name' => 'Admin Search Patient', 'nic_or_passport' => 'SEARCH-001', 'phone_number' => '0770000000'], 'admin', 'Admin Search Patient', 'full_name'],
            'medicine' => [MedicineResource::class, Medicine::class, ['name' => 'Admin Search Medicine', 'category' => 'Thailaya'], 'admin', 'Admin Search Medicine', 'name'],
        ];
    }

    #[DataProvider('searchableResources')]
    public function test_global_search_uses_real_columns_and_returns_record_titles(
        string $resource,
        string $model,
        array $attributes,
        string $search,
        string $title,
        string $column,
    ): void {
        $this->actingAs($this->userWithRole('super_admin'));
        $record = $model::create($attributes);

        $results = $resource::getGlobalSearchResults($search);

        $this->assertCount(1, $results);
        $this->assertSame($title, $results->first()->title);
        $this->assertSame($resource::getUrl('view', ['record' => $record]), $results->first()->url);
        $this->assertSame([$column], $resource::getGloballySearchableAttributes());
        $this->assertCount(0, $resource::getGlobalSearchResults('no-matching-record'));
    }

    public function test_global_search_preserves_role_access_restrictions(): void
    {
        $this->actingAs($this->userWithRole('pharmacy'));

        $this->assertFalse(CountryResource::canGloballySearch());
        $this->assertFalse(PatientResource::canGloballySearch());
        $this->assertTrue(MedicineResource::canGloballySearch());
    }
}
