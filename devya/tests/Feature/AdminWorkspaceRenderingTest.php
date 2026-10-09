<?php

namespace Tests\Feature;

use App\Filament\Pages\Billing;
use App\Filament\Pages\CreateAppointment;
use App\Filament\Pages\DoctorAvailability;
use App\Filament\Resources\Medicines\Pages\CreateMedicine;
use App\Filament\Resources\Patients\Pages\CreatePatient;
use App\Filament\Resources\Staff\Pages\CreateStaff;
use App\Models\Consultation;
use App\Models\ConsultationBill;
use App\Models\Doctor;
use App\Models\Patient;
use DOMDocument;
use DOMXPath;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as PaginatorContract;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\InteractsWithPermissions;
use Tests\TestCase;

class AdminWorkspaceRenderingTest extends TestCase
{
    use InteractsWithPermissions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpPermissions();
        $this->actingAs($this->userWithRole('super_admin'));

        Schema::create('countries', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });
        Schema::create('departments', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->softDeletes();
        });
    }

    public static function registrationPages(): array
    {
        return [
            'patients' => [CreatePatient::class, 'Medical Information'],
            'staff' => [CreateStaff::class, 'Emergency Contact'],
            'medicines' => [CreateMedicine::class, 'Stock & Pricing Settings'],
        ];
    }

    #[DataProvider('registrationPages')]
    public function test_registration_forms_render_without_losing_their_fields(string $page, string $section): void
    {
        Livewire::test($page)
            ->assertSuccessful()
            ->assertSeeText($section)
            ->assertSeeText('Create');
    }

    public function test_appointment_form_renders_and_keeps_required_selection_validation(): void
    {
        Livewire::test(CreateAppointment::class)
            ->assertSuccessful()
            ->assertSeeText('Appointment Details')
            ->assertSeeText('Cancel')
            ->assertSeeText('Create Appointment')
            ->call('save')
            ->assertHasFormErrors(['patient_id' => 'required', 'doctor_id' => 'required']);
    }

    public function test_billing_records_keep_document_actions_and_disable_unconfigured_email(): void
    {
        config(['mail.enabled' => false]);

        $page = Livewire::test(BillingRenderingPage::class)
            ->assertSuccessful()
            ->assertSeeText('CONS-BILL-001')
            ->assertSeeText('Test Patient')
            ->assertSeeText('LKR 2,500.00')
            ->assertSee('Print consultation bill')
            ->assertSee('Prescription')
            ->assertSee('Share on WhatsApp');

        $document = new DOMDocument;
        @$document->loadHTML($page->html());
        $xpath = new DOMXPath($document);
        $this->assertSame(1, $xpath->query('//button[@aria-label="Email delivery is not configured."][@disabled]')->length);

        $page->set('billNumber', 'CONS')
            ->set('patientName', 'Test')
            ->set('billDate', '2026-10-08')
            ->call('clearFilters')
            ->assertSet('billNumber', '')
            ->assertSet('patientName', '')
            ->assertSet('billDate', '');

        config(['mail.enabled' => true, 'mail.default' => 'smtp']);
        $page->set('billNumber', 'CONS')->assertSee('Email bill');
        @$document->loadHTML($page->html());
        $xpath = new DOMXPath($document);
        $this->assertSame(1, $xpath->query('//button[@aria-label="Email bill"][not(@disabled)]')->length);
    }

    public function test_billing_empty_state_renders_without_document_actions(): void
    {
        Livewire::test(BillingRenderingPage::class)
            ->set('emptyRecords', true)
            ->assertSeeText('No consultation bills match these filters.')
            ->assertDontSeeText('CONS-BILL-001')
            ->assertDontSee('Print consultation bill');
    }

    public function test_doctor_records_keep_hours_contact_capacity_and_unicode_initials(): void
    {
        Livewire::test(DoctorAvailabilityRenderingPage::class)
            ->assertSuccessful()
            ->assertSeeText("\u{0DC0} Silva")
            ->assertSeeText('Panchakarma')
            ->assertSeeText('09:00')
            ->assertSeeText('16:00')
            ->assertSeeText('0771234567')
            ->assertSeeText('doctor@example.test')
            ->assertSeeText('7 / 20')
            ->set('doctorSearch', 'Silva')
            ->set('specialization', 'Panchakarma')
            ->call('clearSearch')
            ->assertSet('doctorSearch', '')
            ->assertSet('specialization', '')
            ->assertSet('date', now()->toDateString());
    }

    public function test_doctor_availability_empty_state_renders(): void
    {
        Livewire::test(DoctorAvailabilityRenderingPage::class)
            ->set('emptyRecords', true)
            ->assertSeeText('No available doctors found.')
            ->assertSeeText('0 doctors available');
    }
}

// Render the real page templates with records that do not depend on unrelated tables.
class BillingRenderingPage extends Billing
{
    public bool $emptyRecords = false;

    public function getBills(): PaginatorContract
    {
        $patient = new Patient([
            'full_name' => 'Test Patient',
            'email' => 'patient@example.test',
            'whatsapp_number' => '0771234567',
        ]);
        $consultation = (new Consultation)->forceFill(['id' => 15, 'consultation_number' => 'CONS-001']);
        $consultation->setRelation('patient', $patient);
        $consultation->setRelation('doctor', new Doctor(['name' => 'Test Doctor']));
        $bill = (new ConsultationBill)->forceFill([
            'id' => 25,
            'bill_number' => 'CONS-BILL-001',
            'currency' => 'LKR',
            'grand_total' => 2500,
            'payment_status' => 'paid',
            'created_at' => now(),
        ]);
        $bill->setRelation('consultation', $consultation);

        $records = $this->emptyRecords ? [] : [$bill];

        return new LengthAwarePaginator($records, count($records), 10);
    }
}

class DoctorAvailabilityRenderingPage extends DoctorAvailability
{
    public bool $emptyRecords = false;

    public function getSpecializations(): array
    {
        return ['Panchakarma'];
    }

    public function getDoctors(): Collection
    {
        $doctor = (new Doctor)->forceFill([
            'id' => 1,
            'name' => "\u{0DC0} Silva",
            'specialization' => 'Panchakarma',
            'room_number' => '01',
            'phone_number' => '0771234567',
            'email' => 'doctor@example.test',
            'max_patients_per_day' => 20,
            'booked_count' => 7,
            'remaining_count' => 13,
            'schedule_start' => '09:00',
            'schedule_end' => '16:00',
        ]);

        return collect($this->emptyRecords ? [] : [$doctor]);
    }
}
