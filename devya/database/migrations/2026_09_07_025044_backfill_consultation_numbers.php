<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('consultations')
            ->whereNull('consultation_number')
            ->orderBy('id')
            ->eachById(function (object $consultation): void {
                DB::table('consultations')
                    ->where('id', $consultation->id)
                    ->update([
                        'consultation_number' => sprintf(
                            'CONS-%06d',
                            $consultation->id
                        ),
                    ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('consultations')
            ->where('consultation_number', 'like', 'CONS-%')
            ->update(['consultation_number' => null]);
    }
};
