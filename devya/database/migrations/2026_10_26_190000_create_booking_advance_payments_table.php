<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_advance_payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('online_appointment_id')->constrained()->cascadeOnDelete();
            $table->uuid('uuid')->unique();
            $table->string('order_id', 100)->unique();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('LKR');
            $table->string('status', 20)->default('pending');
            $table->string('payhere_payment_id', 100)->nullable()->unique();
            $table->json('additional_payhere_payment_ids')->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->string('status_message', 255)->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['online_appointment_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_advance_payments');
    }
};
