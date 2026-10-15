<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultation_medicines', function (Blueprint $table) {
            $table->unsignedSmallInteger('breakfast_dose')->default(0)->after('dosage');
            $table->string('breakfast_timing', 10)->nullable()->after('breakfast_dose');
            $table->unsignedSmallInteger('lunch_dose')->default(0)->after('breakfast_timing');
            $table->string('lunch_timing', 10)->nullable()->after('lunch_dose');
            $table->unsignedSmallInteger('dinner_dose')->default(0)->after('lunch_timing');
            $table->string('dinner_timing', 10)->nullable()->after('dinner_dose');
            $table->unsignedSmallInteger('duration_days')->default(1)->after('dinner_timing');
        });
    }

    public function down(): void
    {
        Schema::table('consultation_medicines', function (Blueprint $table) {
            $table->dropColumn([
                'breakfast_dose',
                'breakfast_timing',
                'lunch_dose',
                'lunch_timing',
                'dinner_dose',
                'dinner_timing',
                'duration_days',
            ]);
        });
    }
};
