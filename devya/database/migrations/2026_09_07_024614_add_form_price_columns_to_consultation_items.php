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
        Schema::table('consultation_treatments', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(0.00)->after('treatment_id');
        });

        Schema::table('consultation_medicines', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->default(0.00)->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultation_treatments', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('consultation_medicines', function (Blueprint $table) {
            $table->dropColumn('total_price');
        });
    }
};
