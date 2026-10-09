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
        Schema::table(
            'online_appointments',
            function (Blueprint $table): void {

                $table
                    ->unsignedInteger('token_number')
                    ->nullable()
                    ->after('booking_number');

                /*
                |--------------------------------------------------------------------------
                | INDEX
                |--------------------------------------------------------------------------
                |
                | Token numbers restart for each appointment date,
                | so the token field should be indexed for fast lookup.
                |
                */

                $table->index(
                    'token_number',
                    'online_appointments_token_number_index'
                );
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'online_appointments',
            function (Blueprint $table): void {

                $table->dropIndex(
                    'online_appointments_token_number_index'
                );

                $table->dropColumn(
                    'token_number'
                );
            }
        );
    }
};
