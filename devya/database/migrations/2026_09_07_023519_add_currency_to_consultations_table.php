<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The currency column already exists in the original
        // create_consultations_table migration.
        if (! Schema::hasColumn('consultations', 'currency')) {
            Schema::table('consultations', function (Blueprint $table) {
                $table->string('currency', 5)
                    ->default('LKR')
                    ->after('patient_type');
            });
        }
    }

    public function down(): void
    {
        // Do not remove the column here because it belongs
        // to the original consultations table migration.
    }
};
