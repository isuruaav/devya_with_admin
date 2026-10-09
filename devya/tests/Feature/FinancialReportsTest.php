<?php

namespace Tests\Feature;

use App\Filament\Pages\FinancialReports;
use App\Filament\Pages\IncomeReports;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FinancialReportsTest extends TestCase
{
    protected function tearDown(): void
    {
        Schema::dropIfExists('reception_bills');
        Schema::dropIfExists('online_appointments');
        Schema::dropIfExists('consultation_bills');
        Schema::dropIfExists('consultations');

        parent::tearDown();
    }

    public function test_income_and_financial_reports_include_paid_reception_sales_and_facility_fees(): void
    {
        $this->createReportingTables();
        $this->travelTo('2026-10-05 12:00:00');
        $this->createReportRecords();

        $incomeReport = new IncomeReports;
        $incomeReport->reportDate = '2026-10-05';
        $incomeReport->reportMonth = '2026-10';

        $daily = $incomeReport->getDailyTotals();
        $monthly = $incomeReport->getMonthlyTotals();
        $financialSummary = (new FinancialReports)->getSummary();

        $this->assertSame(1650.0, $daily['local']['total']);
        $this->assertSame(250.0, $daily['local']['facility_service_fees']);
        $this->assertSame(400.0, $daily['local']['reception_bills']);
        $this->assertSame(1, $daily['local']['paid_appointments']);
        $this->assertSame(1, $daily['local']['reception_bills_count']);
        $this->assertSame(45.0, $daily['foreign']['total']);
        $this->assertSame(145.0, $monthly['foreign']['total']);
        $this->assertSame(1760.0, $financialSummary['all_time']['local']);
        $this->assertSame(1650.0, $financialSummary['this_month']['local']);
        $this->assertSame(145.0, $financialSummary['this_month']['foreign']);
    }

    private function createReportingTables(): void
    {
        Schema::create('consultations', function (Blueprint $table): void {
            $table->id();
            $table->string('status');
            $table->dateTime('consultation_date');
        });

        Schema::create('consultation_bills', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('consultation_id');
            $table->string('currency');
            $table->decimal('doctor_fee', 12, 2);
            $table->decimal('treatment_total', 12, 2);
            $table->decimal('medicine_total', 12, 2);
            $table->decimal('grand_total', 12, 2);
        });

        Schema::create('online_appointments', function (Blueprint $table): void {
            $table->id();
            $table->string('payment_status');
            $table->string('invoice_currency');
            $table->decimal('facility_service_fee', 12, 2);
            $table->dateTime('paid_at')->nullable();
        });

        Schema::create('reception_bills', function (Blueprint $table): void {
            $table->id();
            $table->string('payment_status');
            $table->string('currency');
            $table->decimal('grand_total', 12, 2);
            $table->dateTime('paid_at')->nullable();
        });
    }

    private function createReportRecords(): void
    {
        DB::table('consultations')->insert([
            [
                'id' => 1,
                'status' => 'completed',
                'consultation_date' => '2026-10-05 09:00:00',
            ],
            [
                'id' => 2,
                'status' => 'completed',
                'consultation_date' => '2026-10-01 09:00:00',
            ],
            [
                'id' => 3,
                'status' => 'cancelled',
                'consultation_date' => '2026-10-05 10:00:00',
            ],
        ]);

        DB::table('consultation_bills')->insert([
            [
                'id' => 1,
                'consultation_id' => 1,
                'currency' => 'LKR',
                'doctor_fee' => 700,
                'treatment_total' => 300,
                'medicine_total' => 0,
                'grand_total' => 1000,
            ],
            [
                'id' => 2,
                'consultation_id' => 2,
                'currency' => 'USD',
                'doctor_fee' => 100,
                'treatment_total' => 0,
                'medicine_total' => 0,
                'grand_total' => 100,
            ],
            [
                'id' => 3,
                'consultation_id' => 3,
                'currency' => 'LKR',
                'doctor_fee' => 900,
                'treatment_total' => 0,
                'medicine_total' => 0,
                'grand_total' => 900,
            ],
        ]);

        DB::table('online_appointments')->insert([
            [
                'id' => 1,
                'payment_status' => 'paid',
                'invoice_currency' => 'LKR',
                'facility_service_fee' => 250,
                'paid_at' => '2026-10-05 10:00:00',
            ],
            [
                'id' => 2,
                'payment_status' => 'paid',
                'invoice_currency' => 'USD',
                'facility_service_fee' => 20,
                'paid_at' => '2026-10-05 10:00:00',
            ],
            [
                'id' => 3,
                'payment_status' => 'unpaid',
                'invoice_currency' => 'LKR',
                'facility_service_fee' => 999,
                'paid_at' => '2026-10-05 10:00:00',
            ],
            [
                'id' => 4,
                'payment_status' => 'paid',
                'invoice_currency' => 'LKR',
                'facility_service_fee' => 50,
                'paid_at' => '2026-09-30 10:00:00',
            ],
        ]);

        DB::table('reception_bills')->insert([
            [
                'id' => 1,
                'payment_status' => 'paid',
                'currency' => 'LKR',
                'grand_total' => 400,
                'paid_at' => '2026-10-05 11:00:00',
            ],
            [
                'id' => 2,
                'payment_status' => 'paid',
                'currency' => 'USD',
                'grand_total' => 25,
                'paid_at' => '2026-10-05 11:00:00',
            ],
            [
                'id' => 3,
                'payment_status' => 'unpaid',
                'currency' => 'LKR',
                'grand_total' => 900,
                'paid_at' => '2026-10-05 11:00:00',
            ],
            [
                'id' => 4,
                'payment_status' => 'paid',
                'currency' => 'LKR',
                'grand_total' => 60,
                'paid_at' => '2026-09-30 11:00:00',
            ],
        ]);
    }
}
