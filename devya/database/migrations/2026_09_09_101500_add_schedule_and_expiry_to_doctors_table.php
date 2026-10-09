<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table): void {
            $table->string('availability_status')->default('available')->after('is_active');
            $table->json('available_days')->nullable()->after('availability_status');
            $table->time('schedule_start')->nullable()->after('available_days');
            $table->time('schedule_end')->nullable()->after('schedule_start');
            $table->date('slmc_document_expiry')->nullable()->after('schedule_end');
            $table->date('nic_document_expiry')->nullable()->after('slmc_document_expiry');
            $table->index(['availability_status', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table): void {
            $table->dropIndex(['doctors_availability_status_is_active_index']);
            $table->dropColumn([
                'availability_status',
                'available_days',
                'schedule_start',
                'schedule_end',
                'slmc_document_expiry',
                'nic_document_expiry',
            ]);
        });
    }
};
