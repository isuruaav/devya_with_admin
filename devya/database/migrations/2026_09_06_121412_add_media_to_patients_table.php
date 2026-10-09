<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (! Schema::hasColumn('patients', 'patient_photo')) {
                $table->string('patient_photo')->nullable()->after('medical_history');
            }

            if (! Schema::hasColumn('patients', 'nic_passport_copy')) {
                $table->string('nic_passport_copy')->nullable()->after('patient_photo');
            }

            // User Audit Details (තිබේදැයි පරීක්ෂා කර පමණක් එකතු කරයි)
            if (! Schema::hasColumn('patients', 'created_by_user_id')) {
                $table->foreignId('created_by_user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('patients', 'updated_by_user_id')) {
                $table->foreignId('updated_by_user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('patients', 'deleted_by_user_id')) {
                $table->foreignId('deleted_by_user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('patients', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'created_by_user_id')) {
                $table->dropForeign(['created_by_user_id']);
                $table->dropColumn('created_by_user_id');
            }

            if (Schema::hasColumn('patients', 'updated_by_user_id')) {
                $table->dropForeign(['updated_by_user_id']);
                $table->dropColumn('updated_by_user_id');
            }

            if (Schema::hasColumn('patients', 'deleted_by_user_id')) {
                $table->dropForeign(['deleted_by_user_id']);
                $table->dropColumn('deleted_by_user_id');
            }

            $columnsToDrop = array_filter([
                Schema::hasColumn('patients', 'patient_photo') ? 'patient_photo' : null,
                Schema::hasColumn('patients', 'nic_passport_copy') ? 'nic_passport_copy' : null,
            ]);

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }

            if (Schema::hasColumn('patients', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
