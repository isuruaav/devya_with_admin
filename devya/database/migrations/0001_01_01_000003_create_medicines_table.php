<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ඖෂධයේ නම (e.g. Siddharthaka Thailaya)
            $table->string('code')->nullable()->unique(); // Code/SKU (e.g. MED-001)
            $table->string('category'); // (e.g. Thailaya, Arishta, Churna, Vati/Guli, Powder, Syrup)

            $table->string('unit')->default('pcs'); // Unit of Measurement (e.g. ml, grams, bottles, tablets, pcs)
            $table->decimal('unit_price', 10, 2)->default(0.00); // විකුණුම් මිල - Local (LKR)
            $table->decimal('foreign_price', 10, 2)->nullable()->default(0.00); // විදේශිකයින්ගේ මිල - Foreign (USD)
            $table->decimal('cost_price', 10, 2)->nullable()->default(0.00); // ගත් මිල (Cost Price)

            // Stock Control
            $table->integer('stock_quantity')->default(0); // දැනට තිබෙන තොගය
            $table->integer('reorder_level')->default(10); // තොග අඩුවූ විට Alert දෙන සීමාව

            $table->text('description')->nullable(); // අඩංගු දේ හෝ උපදෙස්
            $table->boolean('is_active')->default(true);

            // Audit details
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
