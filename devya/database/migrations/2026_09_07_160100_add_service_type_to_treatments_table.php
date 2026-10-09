<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('treatments', function (Blueprint $table): void {
            $table->string('service_type')->default('treatment')->after('name');
        });

        $services = [
            ['code' => 'SALON-FACIAL', 'name' => 'Ayurvedic Facial'],
            ['code' => 'SALON-HEAD-MASSAGE', 'name' => 'Head Massage / Shirodhara'],
            ['code' => 'SALON-HAIR-CARE', 'name' => 'Hair Care'],
            ['code' => 'SALON-HERBAL-STEAM', 'name' => 'Herbal Steam'],
            ['code' => 'SALON-BODY-TREATMENT', 'name' => 'Herbal Body Treatments'],
            ['code' => 'SALON-ABHYANGA', 'name' => 'Abhyanga (Ayurvedic Oil Massage)'],
            ['code' => 'SALON-BEAUTY-CARE', 'name' => 'Beauty-care Services'],
        ];

        foreach ($services as $service) {
            DB::table('treatments')->updateOrInsert(
                ['code' => $service['code']],
                [
                    'name' => $service['name'],
                    'service_type' => 'salon',
                    'duration_minutes' => 30,
                    'local_price' => 0,
                    'foreign_price' => 0,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }

    public function down(): void
    {
        DB::table('treatments')
            ->where('service_type', 'salon')
            ->delete();

        Schema::table('treatments', function (Blueprint $table): void {
            $table->dropColumn('service_type');
        });
    }
};
