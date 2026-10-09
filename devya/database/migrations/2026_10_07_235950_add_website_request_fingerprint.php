<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('online_appointments', function (Blueprint $table): void {
            $table->string('website_request_fingerprint', 64)->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('online_appointments', function (Blueprint $table): void {
            $table->dropColumn('website_request_fingerprint');
        });
    }
};
