<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * patient_id already exists in online_appointments table.
         * Therefore this migration only needs to add source.
         */

        if (! Schema::hasColumn('online_appointments', 'source')) {
            Schema::table('online_appointments', function (Blueprint $table) {
                $table->string('source')
                    ->default('Online')
                    ->after('status');

                $table->index('source');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('online_appointments', 'source')) {
            Schema::table('online_appointments', function (Blueprint $table) {
                $table->dropIndex(['source']);
                $table->dropColumn('source');
            });
        }
    }
};
