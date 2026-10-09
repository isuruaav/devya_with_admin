<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('doctor_id')->nullable()->after('role')->constrained('doctors')->nullOnDelete();
            $table->index(['role', 'doctor_id']);
        });

        Schema::create('doctor_audits', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_audits');
        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['doctor_id']);
            $table->dropIndex(['role', 'doctor_id']);
            $table->dropColumn('doctor_id');
        });
    }
};
