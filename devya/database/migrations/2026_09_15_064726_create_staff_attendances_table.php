<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('staff_id')
                ->constrained('staff')
                ->cascadeOnDelete();

            $table->date('attendance_date');

            $table->dateTime('check_in');

            $table->dateTime('check_out')->nullable();

            $table->string('check_in_method')
                ->nullable();

            $table->string('check_out_method')
                ->nullable();

            $table->string('status')
                ->default('Present');

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            // Staff + Date search performance
            $table->index([
                'staff_id',
                'attendance_date',
            ]);

            // Date + Status reports
            $table->index([
                'attendance_date',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_attendances');
    }
};
