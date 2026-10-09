<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_weekly_schedules', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            $table->string('day_of_week');

            $table->boolean('is_available')
                ->default(false);

            $table->time('start_time')
                ->nullable();

            $table->time('end_time')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'doctor_id',
                'day_of_week',
            ]);

            $table->index([
                'doctor_id',
                'is_available',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_weekly_schedules');
    }
};
