<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table): void {
            $table->unsignedInteger('queue_number')->nullable()->after('consultation_number');
            $table->string('queue_status')->default('waiting')->after('status');
            $table->timestamp('doctor_seen_at')->nullable()->after('queue_status');
            $table->index(['consultation_date', 'queue_status']);
            $table->index(['doctor_id', 'consultation_date', 'queue_number']);
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table): void {
            $table->dropIndex(['consultations_consultation_date_queue_status_index']);
            $table->dropIndex(['consultations_doctor_id_consultation_date_queue_number_index']);
            $table->dropColumn(['queue_number', 'queue_status', 'doctor_seen_at']);
        });
    }
};
