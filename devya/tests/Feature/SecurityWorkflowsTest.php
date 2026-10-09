<?php

namespace Tests\Feature;

use App\Console\Commands\PrivatizeDocuments;
use App\Filament\Pages\MedicalHistory;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\MedicalReport;
use App\Models\OnlineAppointment;
use App\Models\Patient;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\StaffAttendanceRequest;
use App\Models\User;
use App\Support\OutboundMail;
use Database\Seeders\RolesAndPermissionsSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SecurityWorkflowsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        Filament::bootCurrentPanel();
    }

    public static function appointmentDocuments(): array
    {
        return [['invoice'], ['receipt'], ['print'], ['receipt/print']];
    }

    #[DataProvider('appointmentDocuments')]
    public function test_pharmacy_cannot_access_appointment_billing_documents(string $document): void
    {
        $this->actingAs($this->user('pharmacy'));
        $this->get('/admin/billing/appointment/0/'.$document)->assertForbidden();
    }

    public function test_reception_cannot_modify_staff_attendance(): void
    {
        $staff = $this->staff();
        $pending = StaffAttendanceRequest::create([
            'staff_id' => $staff->id, 'attendance_date' => today(), 'requested_at' => now(),
            'attendance_type' => 'IN', 'method' => 'NIC', 'status' => 'Pending',
        ]);
        $this->actingAs($this->user('reception'));

        $this->postJson('/staff-attendance/scan', ['token' => $staff->qr_token])->assertForbidden();
        $this->postJson('/staff-attendance/manual', ['identifier' => $staff->nic_passport])->assertForbidden();
        $this->postJson('/staff-attendance/requests/'.$pending->id.'/approve')->assertForbidden();
        $this->postJson('/staff-attendance/requests/'.$pending->id.'/reject', ['rejection_reason' => 'Test'])->assertForbidden();
        $this->assertSame('Pending', $pending->fresh()->status);
        $this->assertSame(0, StaffAttendance::count());
    }

    public function test_admin_can_scan_staff_in_and_out_with_the_attendance_model(): void
    {
        $staff = $this->staff();
        $this->actingAs($this->user('admin'));
        $this->postJson('/staff-attendance/scan', ['token' => $staff->qr_token])
            ->assertOk()->assertJsonPath('action', 'IN');
        $this->travel(30)->minutes();
        $this->postJson('/staff-attendance/scan', ['token' => $staff->qr_token])
            ->assertOk()->assertJsonPath('action', 'OUT');

        $attendance = $staff->attendances()->firstOrFail();
        $this->assertNotNull($attendance->check_out);
        $this->assertTrue($attendance->check_out->greaterThan($attendance->check_in));
        $this->assertSame($staff->id, $attendance->staff->id);
    }

    public function test_admin_can_approve_manual_attendance(): void
    {
        $staff = $this->staff();
        $this->actingAs($this->user('admin'));
        $response = $this->postJson('/staff-attendance/manual', ['identifier' => $staff->nic_passport])
            ->assertOk()->assertJsonPath('status', 'Pending');
        $id = $response->json('request_id');
        $this->postJson('/staff-attendance/requests/'.$id.'/approve')->assertOk()->assertJsonPath('success', true);
        $this->assertSame('Approved', StaffAttendanceRequest::findOrFail($id)->status);
        $this->assertSame(1, $staff->attendances()->count());
    }

    public function test_patient_document_requires_login_and_the_correct_permission(): void
    {
        Storage::fake('confidential');
        $path = 'patients/documents/test.pdf';
        Storage::disk('confidential')->put($path, 'private-document-content');
        $this->patient(['nic_passport_copy' => $path]);
        $url = $this->documentUrl($path);
        $this->get($url)->assertRedirect(route('filament.admin.auth.login'));
        $this->actingAs($this->user('pharmacy'))->get($url)->assertForbidden();
        $this->actingAs($this->user('super_admin'))->get($url)
            ->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertStreamedContent('private-document-content');
        $this->get($url.'&path=patients/documents/other.pdf')->assertForbidden();
    }

    public function test_rejected_attendance_cannot_be_processed_again(): void
    {
        $staff = $this->staff();
        $pending = StaffAttendanceRequest::create([
            'staff_id' => $staff->id, 'attendance_date' => today(), 'requested_at' => now(),
            'attendance_type' => 'IN', 'method' => 'NIC', 'status' => 'Pending',
        ]);
        $this->actingAs($this->user('admin'));
        $url = '/staff-attendance/requests/'.$pending->id;
        $this->postJson($url.'/reject', ['rejection_reason' => 'Invalid request'])->assertOk();
        $this->postJson($url.'/reject', ['rejection_reason' => 'Invalid request'])->assertNotFound();
        $this->postJson($url.'/approve')->assertJsonPath('success', false);
        $this->assertSame('Rejected', $pending->fresh()->status);
        $this->assertSame(0, StaffAttendance::count());
    }

    public function test_email_linked_doctor_only_sees_own_history_and_documents(): void
    {
        Storage::fake('confidential');
        $doctor = $this->doctor('doctor@example.test');
        $otherDoctor = $this->doctor('other@example.test');
        $ownPatient = $this->patient();
        $otherPatient = $this->patient();
        $ownConsultation = $this->consultation($ownPatient, $doctor);
        $this->consultation($otherPatient, $otherDoctor);
        $own = $this->report($ownPatient);
        $other = $this->report($otherPatient);
        Storage::disk('confidential')->put($own->file_path, 'own-report');
        Storage::disk('confidential')->put($other->file_path, 'other-report');
        $user = User::factory()->withRole('doctor')->create(['email' => $doctor->email, 'is_active' => true, 'doctor_id' => null]);
        $this->actingAs($user);

        $page = new MedicalHistory;
        $this->assertSame([$ownConsultation->id], $page->getMedicalRecords()->getCollection()->modelKeys());
        $page->openPatient($ownPatient->id);
        $this->assertSame($ownPatient->id, $page->selectedPatientId);
        $this->get($this->documentUrl($own->file_path))->assertOk()->assertStreamedContent('own-report');
        $this->get($this->documentUrl($other->file_path))->assertForbidden();

        $doctor->update(['is_active' => false]);
        $this->assertCount(0, (new MedicalHistory)->getMedicalRecords());
        $this->get($this->documentUrl($own->file_path))->assertForbidden();
    }

    public function test_report_upload_uses_private_storage(): void
    {
        Storage::fake('confidential');
        Storage::fake('public');
        $patient = $this->patient();
        $this->actingAs($this->user('super_admin'));
        Livewire::test(MedicalHistory::class)
            ->call('openPatient', $patient->id)
            ->set('reportType', 'Laboratory')
            ->set('reportName', 'Test Report')
            ->set('reportFile', UploadedFile::fake()->create('report.pdf', 1, 'application/pdf'))
            ->call('uploadReport')->assertHasNoErrors();

        $report = MedicalReport::firstOrFail();
        Storage::disk('confidential')->assertExists($report->file_path);
        Storage::disk('public')->assertMissing($report->file_path);
    }

    public function test_document_paths_cannot_escape_private_storage(): void
    {
        $this->actingAs($this->user('super_admin'));
        $this->get($this->documentUrl('../../.env'))->assertNotFound();
        $this->get($this->documentUrl('patients/documents/missing.pdf'))->assertNotFound();
        $this->get($this->documentUrl('patients/documents/missing.pdf', 'public'))->assertNotFound();
    }

    public function test_document_migration_verifies_and_preserves_legacy_content(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        Storage::fake('confidential');
        $patient = $this->patient(['nic_passport_copy' => 'patients/documents/nic.pdf']);
        $report = $this->report($patient);
        Storage::disk('public')->put($patient->nic_passport_copy, 'identity-copy');
        Storage::disk('public')->put($report->file_path, 'medical-report');
        Storage::disk('public')->put('patients/photos/test.jpg', 'public-photo');

        $this->artisan('documents:privatize')->assertSuccessful();
        $this->assertSame('identity-copy', Storage::disk('confidential')->get($patient->nic_passport_copy));
        $this->assertSame('medical-report', Storage::disk('confidential')->get($report->file_path));
        Storage::disk('public')->assertMissing($report->file_path);
        Storage::disk('public')->assertExists('patients/photos/test.jpg');
        $this->artisan('documents:privatize')->assertSuccessful();
    }

    public function test_duplicate_website_requests_reuse_the_booking(): void
    {
        $payload = $this->appointmentPayload();
        $first = $this->postJson('/website-appointment', $payload)->assertCreated();
        $second = $this->postJson('/website-appointment', $payload)->assertCreated();
        $this->assertSame($first->json('booking_number'), $second->json('booking_number'));
        $this->assertSame(1, OnlineAppointment::count());
        $this->assertNull(OnlineAppointment::first()->doctor_id);
        $this->assertSame('pending', OnlineAppointment::first()->status);
    }

    public function test_document_migration_does_not_overwrite_conflicting_files(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        Storage::fake('confidential');
        $path = 'patients/documents/conflict.pdf';
        Storage::disk('public')->put($path, 'original-copy');
        Storage::disk('confidential')->put($path, 'existing-private-copy');
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Conflicting private file found');

        try {
            app(PrivatizeDocuments::class)->handle();
        } finally {
            $this->assertSame('original-copy', Storage::disk('public')->get($path));
            $this->assertSame('existing-private-copy', Storage::disk('confidential')->get($path));
        }
    }

    public function test_patient_selection_cannot_be_forged_in_livewire(): void
    {
        $doctor = $this->doctor('doctor@example.test');
        $patient = $this->patient();
        $other = $this->patient();
        $this->consultation($patient, $doctor);
        $user = User::factory()->withRole('doctor')->create(['email' => $doctor->email, 'is_active' => true]);
        $this->actingAs($user);
        $this->expectException(CannotUpdateLockedPropertyException::class);
        Livewire::test(MedicalHistory::class)->call('openPatient', $patient->id)->set('selectedPatientId', $other->id);
    }

    public function test_public_appointment_rate_limit_and_honeypot(): void
    {
        $payload = $this->appointmentPayload();
        $this->postJson('/website-appointment', [...$payload, 'website' => 'spam'])->assertUnprocessable();
        $this->assertSame(0, OnlineAppointment::count());
        for ($i = 0; $i < 4; $i++) {
            $this->postJson('/website-appointment', $payload)->assertCreated();
        }
        $this->postJson('/website-appointment', $payload)->assertTooManyRequests();
        $this->assertSame(1, OnlineAppointment::count());
    }

    public function test_a_new_website_request_is_allowed_after_the_duplicate_window(): void
    {
        $payload = $this->appointmentPayload();
        $first = $this->postJson('/website-appointment', $payload)->assertCreated();
        $this->travel(21)->minutes();
        $second = $this->postJson('/website-appointment', $payload)->assertCreated();
        $this->assertNotSame($first->json('booking_number'), $second->json('booking_number'));
        $this->assertSame(2, OnlineAppointment::count());
    }

    public function test_email_is_not_reported_as_sent_by_a_logging_mailer(): void
    {
        config(['mail.enabled' => true, 'mail.default' => 'log']);
        $this->assertFalse(OutboundMail::isEnabled());
        $this->actingAs($this->user('admin'));
        $this->postJson('/admin/billing/0/email')->assertUnprocessable()->assertJsonValidationErrors('email');
        config(['mail.default' => 'smtp']);
        $this->assertTrue(OutboundMail::isEnabled());
    }

    private function user(string $role): User
    {
        return User::factory()->withRole($role)->create(['is_active' => true]);
    }

    private function staff(): Staff
    {
        return Staff::create(['staff_code' => 'STAFF-001', 'full_name' => 'Staff Test', 'nic_passport' => 'STAFF-NIC-001', 'designation' => 'Assistant', 'joining_date' => today(), 'status' => 'Active']);
    }

    private function patient(array $attributes = []): Patient
    {
        return Patient::create([...['full_name' => 'Patient Test', 'nic_or_passport' => 'PATIENT-'.uniqid(), 'phone_number' => '0770000000', 'patient_type' => 'Local'], ...$attributes]);
    }

    private function doctor(string $email): Doctor
    {
        return Doctor::create(['name' => 'Doctor Test', 'email' => $email, 'phone_number' => '0770000000', 'is_active' => true, 'availability_status' => 'available']);
    }

    private function consultation(Patient $patient, Doctor $doctor): Consultation
    {
        return Consultation::create(['patient_id' => $patient->id, 'doctor_id' => $doctor->id, 'consultation_date' => today(), 'patient_type' => 'Local', 'currency' => 'LKR', 'doctor_fee' => 100, 'status' => 'pending']);
    }

    private function report(Patient $patient): MedicalReport
    {
        return MedicalReport::create(['patient_id' => $patient->id, 'report_name' => 'Test Report', 'file_path' => 'patients/reports/'.$patient->id.'/report.pdf', 'original_file_name' => 'report.pdf']);
    }

    private function documentUrl(string $path, string $disk = 'confidential'): string
    {
        return URL::temporarySignedRoute('documents.show', now()->addMinutes(30), ['path' => $path, 'disk' => $disk]);
    }

    private function appointmentPayload(): array
    {
        return ['fullName' => 'Website Test', 'phone' => '0770000000', 'service' => 'Ayurveda Consultation', 'preferredDate' => today()->addDay()->toDateString(), 'preferredTime' => 'Morning', 'contactMethod' => 'Phone call'];
    }
}
