<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reception_bills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->nullable()
                ->constrained('patients')
                ->nullOnDelete();

            $table->string('customer_name')->nullable();
            $table->string('bill_number')->nullable()->unique();
            $table->string('currency', 10)->default('LKR');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->decimal('amount_received', 12, 2)->default(0);
            $table->decimal('change_amount', 12, 2)->default(0);
            $table->decimal('balance_due', 12, 2)->default(0);

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

            $table->index(['payment_status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reception_bills');
    }
};
