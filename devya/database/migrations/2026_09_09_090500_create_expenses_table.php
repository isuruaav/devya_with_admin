<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table): void {
            $table->id();
            $table->string('category');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('LKR');
            $table->date('expense_date');
            $table->string('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['expense_date', 'currency']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
