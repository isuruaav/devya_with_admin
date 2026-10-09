<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('online_appointments', function (Blueprint $table) {
            $table->decimal('appointment_fee', 12, 2)
                ->nullable()
                ->after('appointment_date');

            $table->string('invoice_currency', 3)
                ->default('LKR')
                ->after('appointment_fee');

            $table->string('invoice_number', 50)
                ->nullable()
                ->unique()
                ->after('invoice_currency');

            $table->dateTime('invoice_issued_at')
                ->nullable()
                ->after('invoice_number');

            $table->string('payment_status')
                ->default('unpaid')
                ->after('invoice_issued_at');

            $table->string('payment_method')
                ->nullable()
                ->after('payment_status');

            $table->string('payment_reference')
                ->nullable()
                ->after('payment_method');

            $table->dateTime('paid_at')
                ->nullable()
                ->after('payment_reference');

            $table->string('receipt_number', 50)
                ->nullable()
                ->unique()
                ->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('online_appointments', function (Blueprint $table) {
            $table->dropUnique(['invoice_number']);
            $table->dropUnique(['receipt_number']);

            $table->dropColumn([
                'appointment_fee',
                'invoice_currency',
                'invoice_number',
                'invoice_issued_at',
                'payment_status',
                'payment_method',
                'payment_reference',
                'paid_at',
                'receipt_number',
            ]);
        });
    }
};
