<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('staff_id')
                ->constrained('staff')
                ->cascadeOnDelete();

            $table->enum('document_type', [
                'NIC / Passport',
                'Birth Certificate',
                'GN Certificate',
                'Appointment Letter',
                'Educational Certificate',
                'Professional Certificate',
                'Service Certificate',
                'Medical Certificate',
                'Other',
            ]);

            $table->string('title');

            $table->string('file_path');

            $table->string('original_file_name')->nullable();

            $table->string('file_type')->nullable();

            $table->unsignedBigInteger('file_size')->nullable();

            $table->text('description')->nullable();

            $table->date('document_date')->nullable();

            $table->boolean('is_current')->default(true);

            $table->timestamps();

            $table->softDeletes();

            $table->index(['staff_id', 'document_type']);
            $table->index('is_current');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_documents');
    }
};
