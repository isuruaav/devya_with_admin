<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('consultation_number')->unique()->nullable();

            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->string('patient_type')->nullable(); // OP / IP / Direct
            $table->string('currency', 5)->default('LKR');

            $table->dateTime('consultation_date')->useCurrent();
            $table->text('symptoms_and_notes')->nullable();

            $table->decimal('doctor_fee', 10, 2)->default(0.00);
            $table->decimal('treatment_total', 10, 2)->default(0.00);
            $table->decimal('medicine_total', 10, 2)->default(0.00);
            $table->decimal('grand_total', 10, 2)->default(0.00);

            $table->string('status')->default('pending');

            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
