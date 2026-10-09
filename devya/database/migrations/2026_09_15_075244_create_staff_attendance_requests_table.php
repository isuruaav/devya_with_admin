<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_attendance_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('staff_id')
                ->constrained('staff')
                ->cascadeOnDelete();

            $table->date('attendance_date');

            $table->dateTime('requested_at');

            $table->string('attendance_type');
            // IN / OUT

            $table->string('method')
                ->default('NIC');
            // NIC

            $table->string('status')
                ->default('Pending');
            // Pending / Approved / Rejected

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('approved_at')
                ->nullable();

            $table->text('rejection_reason')
                ->nullable();

            $table->string('device_ip')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->index([
                'staff_id',
                'attendance_date',
            ]);

            $table->index([
                'status',
                'requested_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_attendance_requests');
    }
};
