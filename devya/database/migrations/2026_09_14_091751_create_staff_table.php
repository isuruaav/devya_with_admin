<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();

            // Staff Identification
            $table->string('staff_code')->unique();

            // Personal Details
            $table->string('full_name');
            $table->string('name_with_initials')->nullable();
            $table->string('nic_passport')->unique();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->default('Male');

            // Contact Details
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();

            // Employment Details
            $table->string('designation');
            $table->string('department')->nullable();
            $table->date('joining_date');
            $table->enum('employment_type', [
                'Permanent',
                'Temporary',
                'Contract',
                'Part Time',
            ])->default('Permanent');

            // Salary
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('allowance', 12, 2)->default(0);

            // Staff Photo
            $table->string('photo')->nullable();

            // Status
            $table->enum('status', ['Active', 'Inactive'])->default('Active');

            // Additional Information
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
