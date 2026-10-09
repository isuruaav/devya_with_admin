<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table): void {
            $table->string('slmc_registration_document')->nullable()->after('reg_no');
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table): void {
            $table->dropColumn('slmc_registration_document');
        });
    }
};
