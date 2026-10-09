<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_bills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('consultation_id')
                ->unique()
                ->constrained('consultations')
                ->cascadeOnDelete();

            $table->string('bill_number')
                ->unique();

            $table->decimal('doctor_fee', 12, 2)->default(0);
            $table->decimal('treatment_total', 12, 2)->default(0);
            $table->decimal('medicine_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);

            $table->string('currency', 10)->default('LKR');

            $table->enum('payment_status', [
                'unpaid',
                'paid',
                'partial',
                'cancelled',
            ])->default('unpaid');

            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();

            $table->string('receipt_number')->nullable()->unique();
            $table->timestamp('paid_at')->nullable();

            $table->foreignId('created_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('paid_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_bills');
    }
};
