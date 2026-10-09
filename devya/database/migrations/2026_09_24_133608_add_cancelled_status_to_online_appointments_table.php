<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('online_appointments', function (Blueprint $table): void {
            $table->enum('status', ['pending', 'confirmed', 'rejected', 'cancelled'])
                ->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('online_appointments', function (Blueprint $table): void {
            $table->enum('status', ['pending', 'confirmed', 'rejected'])
                ->default('pending')->change();
        });
    }
};
