<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ConsultationMedicineDosageMigrationTest extends TestCase
{
    public function test_migration_adds_and_removes_meal_dosage_columns(): void
    {
        $connection = 'consultation_migration_test';
        $originalDefault = config('database.default');
        $connectionConfig = config('database.connections.sqlite');
        $connectionConfig['database'] = ':memory:';

        config([
            "database.connections.{$connection}" => $connectionConfig,
            'database.default' => $connection,
        ]);
        DB::purge($connection);

        $schema = Schema::connection($connection);

        try {
            $schema->create('consultation_medicines', function (Blueprint $table): void {
                $table->id();
                $table->string('dosage')->nullable();
                $table->integer('quantity')->default(1);
            });

            $migration = require database_path(
                'migrations/2026_10_14_141552_add_meal_dosage_fields_to_consultation_medicines_table.php'
            );
            $migration->up();

            $this->assertTrue($schema->hasColumns('consultation_medicines', [
                'breakfast_dose',
                'breakfast_timing',
                'lunch_dose',
                'lunch_timing',
                'dinner_dose',
                'dinner_timing',
                'duration_days',
            ]));

            $migration->down();

            $this->assertFalse($schema->hasColumns('consultation_medicines', [
                'breakfast_dose',
                'breakfast_timing',
                'lunch_dose',
                'lunch_timing',
                'dinner_dose',
                'dinner_timing',
                'duration_days',
            ]));
        } finally {
            $schema->dropIfExists('consultation_medicines');
            DB::purge($connection);
            config([
                'database.default' => $originalDefault,
                "database.connections.{$connection}" => null,
            ]);
        }
    }
}
