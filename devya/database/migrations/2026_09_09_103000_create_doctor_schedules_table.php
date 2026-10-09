<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_schedules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->date('schedule_date');
            $table->string('status')->default('available');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
            $table->unique(['doctor_id', 'schedule_date']);
            $table->index(['schedule_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
