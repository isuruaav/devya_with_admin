<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('patients', 'whatsapp_number')) {
            Schema::table('patients', function (Blueprint $table): void {
                $table->string('whatsapp_number')->nullable()->after('phone_number');
            });
        }

        if (! Schema::hasColumn('patients', 'email')) {
            Schema::table('patients', function (Blueprint $table): void {
                $table->string('email')->nullable()->after('whatsapp_number');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('patients', 'whatsapp_number')) {
            Schema::table('patients', function (Blueprint $table): void {
                $table->dropColumn('whatsapp_number');
            });
        }
    }
};
