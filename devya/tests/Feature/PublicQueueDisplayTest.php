<?php

namespace Tests\Feature;

use App\Http\Controllers\PublicQueueDisplayController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PublicQueueDisplayTest extends TestCase
{
    protected function tearDown(): void
    {
        Schema::dropIfExists('consultations');
        Schema::dropIfExists('online_appointments');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('doctors');

        parent::tearDown();
    }

    public function test_queue_data_includes_the_doctor_specialization(): void
    {
        $this->travelTo('2026-10-01 15:43:32');
        $this->createQueueTables();
        DB::table('doctors')->insert([
            'id' => 1,
            'name' => 'Dr. Test',
            'specialization' => 'Ayurvedic Physician',
            'room_number' => '1',
            'is_active' => true,
        ]);
        DB::table('patients')->insert([
            'id' => 1,
            'full_name' => 'Test Patient',
        ]);
        DB::table('online_appointments')->insert([
            'id' => 1,
            'doctor_id' => 1,
            'patient_id' => 1,
            'appointment_date' => '2026-10-01 15:00:00',
            'status' => 'confirmed',
            'token_number' => 1,
            'payment_status' => 'pending',
        ]);

        $response = app(PublicQueueDisplayController::class)
            ->data(Request::create('/queue-display/data', 'GET', [
                'room' => '1',
            ]));

        $data = $response->getData(true);

        $this->assertSame('Dr. Test', $data['doctor_name']);
        $this->assertSame('Ayurvedic Physician', $data['doctor_specialization']);
    }

    public function test_queue_page_has_a_place_to_display_doctor_specialization(): void
    {
        $response = app(PublicQueueDisplayController::class)
            ->index(Request::create('/queue-display', 'GET', [
                'room' => '1',
            ]));

        $html = $response->render();

        $this->assertStringContainsString('id="doctor-specialization"', $html);
        $this->assertStringContainsString('Specialization', $html);
    }

    private function createQueueTables(): void
    {
        Schema::create('doctors', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('specialization')->nullable();
            $table->string('room_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
        });

        Schema::create('patients', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name');
            $table->softDeletes();
        });

        Schema::create('online_appointments', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');
            $table->dateTime('appointment_date');
            $table->string('status');
            $table->unsignedInteger('token_number')->nullable();
            $table->unsignedBigInteger('consultation_id')->nullable();
            $table->string('payment_status')->default('pending');
        });

        Schema::create('consultations', function (Blueprint $table): void {
            $table->id();
            $table->string('queue_status')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('doctor_seen_at')->nullable();
        });
    }
}
