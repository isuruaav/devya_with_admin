<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultation_bills', function (Blueprint $table) {
            $table->decimal('amount_received', 12, 2)
                ->default(0)
                ->after('balance_due');

            $table->decimal('change_amount', 12, 2)
                ->default(0)
                ->after('amount_received');
        });
    }

    public function down(): void
    {
        Schema::table('consultation_bills', function (Blueprint $table) {
            $table->dropColumn([
                'amount_received',
                'change_amount',
            ]);
        });
    }
};
