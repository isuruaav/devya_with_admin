<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nic_number')->nullable();
            $table->string('doctor_photo')->nullable();
            $table->string('nic_copy')->nullable();

            $table->string('reg_no')->nullable();
            $table->string('specialization')->nullable();
            $table->string('qualification')->nullable();

            // Contact details
            $table->string('phone_number');
            $table->string('whatsapp_number')->nullable();
            $table->string('email')->nullable();

            // Channeling / OPD Details
            $table->string('room_number')->nullable();
            $table->integer('max_patients_per_day')->nullable()->default(20);

            // Fees
            $table->decimal('local_fee', 10, 2)->default(0.00);
            $table->decimal('foreign_fee', 10, 2)->default(0.00);

            $table->boolean('is_active')->default(true);

            // Audit columns
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
