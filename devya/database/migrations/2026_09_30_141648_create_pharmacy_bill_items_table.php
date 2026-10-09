<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pharmacy_bill_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pharmacy_bill_id')
                ->constrained('pharmacy_bills')
                ->cascadeOnDelete();

            $table->foreignId('medicine_id')
                ->constrained('medicines')
                ->restrictOnDelete();

            // Medicine name saved as a snapshot for historical bills.
            $table->string('medicine_name');

            $table->decimal('quantity', 12, 2)->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            // Stock is deducted only when Pharmacy issues the medicine.
            $table->decimal('issued_quantity', 12, 2)->default(0);

            $table->foreignId('issued_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('issued_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_bill_items');
    }
};
