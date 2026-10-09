<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('online_appointments', function (Blueprint $table): void {
            $table->id();
            $table->string('booking_number')->unique();
            $table->string('full_name');
            $table->string('nic_or_passport');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->enum('patient_type', ['Local', 'Foreign'])->default('Local');
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->dateTime('appointment_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->foreignId('consultation_id')->nullable()->constrained('consultations')->nullOnDelete();
            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('online_appointments');
    }
};
