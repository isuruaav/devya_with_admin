<?php

namespace Tests\Unit\Models;

use App\Models\Doctor;
use Tests\TestCase;

class DoctorTest extends TestCase
{
    public function test_consultation_fee_uses_local_fee_for_local_patients(): void
    {
        $doctor = new Doctor([
            'local_fee' => 1750,
            'foreign_fee' => 35,
        ]);

        $this->assertSame(1750.0, $doctor->consultationFeeFor('Local'));
    }

    public function test_consultation_fee_uses_foreign_fee_for_foreign_patients(): void
    {
        $doctor = new Doctor([
            'local_fee' => 1750,
            'foreign_fee' => 35,
        ]);

        $this->assertSame(35.0, $doctor->consultationFeeFor('Foreign'));
    }
}
