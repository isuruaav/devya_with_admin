<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('online_appointments', function (Blueprint $table) {
            $table->foreignId('patient_id')
                ->nullable()
                ->after('id')
                ->constrained('patients')
                ->nullOnDelete();

            $table->string('source')
                ->default('Online')
                ->after('status');

            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::table('online_appointments', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropIndex(['source']);
            $table->dropColumn(['patient_id', 'source']);
        });
    }
};
