<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Shirodhara, Full Body Abhyanga, Steam Bath
            $table->string('code')->nullable()->unique(); // TRT-001
            $table->integer('duration_minutes')->nullable()->default(30); // ගතවන කාලය (මිලිනිටු)

            // Charges
            $table->decimal('local_price', 10, 2)->default(0.00);
            $table->decimal('foreign_price', 10, 2)->default(0.00);

            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
