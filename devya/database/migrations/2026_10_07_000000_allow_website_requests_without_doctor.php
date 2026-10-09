<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('online_appointments', function (Blueprint $table): void {
                $table->foreignId('doctor_id')->nullable()->change();
            });

            return;
        }

        Schema::table('online_appointments', function (Blueprint $table): void {
            $table->dropForeign(['doctor_id']);
        });

        DB::statement('ALTER TABLE online_appointments MODIFY doctor_id BIGINT UNSIGNED NULL');

        Schema::table('online_appointments', function (Blueprint $table): void {
            $table->foreign('doctor_id')
                ->references('id')
                ->on('doctors')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (DB::table('online_appointments')->whereNull('doctor_id')->exists()) {
            throw new RuntimeException('Assign doctors to existing website requests before reverting this migration.');
        }

        if (DB::getDriverName() === 'sqlite') {
            Schema::table('online_appointments', function (Blueprint $table): void {
                $table->foreignId('doctor_id')->nullable(false)->change();
            });

            return;
        }

        Schema::table('online_appointments', function (Blueprint $table): void {
            $table->dropForeign(['doctor_id']);
        });

        DB::statement('ALTER TABLE online_appointments MODIFY doctor_id BIGINT UNSIGNED NOT NULL');

        Schema::table('online_appointments', function (Blueprint $table): void {
            $table->foreign('doctor_id')
                ->references('id')
                ->on('doctors')
                ->cascadeOnDelete();
        });
    }
};
