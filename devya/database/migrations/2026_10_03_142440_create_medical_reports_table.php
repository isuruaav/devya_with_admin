<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('consultation_id')
                ->nullable()
                ->constrained('consultations')
                ->nullOnDelete();

            $table->string('report_type')->nullable();

            $table->string('report_name');

            $table->date('report_date')->nullable();

            $table->string('hospital_lab')->nullable();

            $table->text('description')->nullable();

            $table->string('file_path');

            $table->string('original_file_name')->nullable();

            $table->string('file_extension', 20)->nullable();

            $table->unsignedBigInteger('file_size')->nullable();

            $table->foreignId('uploaded_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index([
                'patient_id',
                'report_date',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_reports');
    }
};
