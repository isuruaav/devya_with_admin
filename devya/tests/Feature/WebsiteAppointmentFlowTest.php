<?php

namespace Tests\Feature;

use App\Filament\Resources\OnlineAppointments\OnlineAppointmentResource;
use App\Filament\Resources\OnlineAppointments\Pages\ListOnlineAppointments;
use App\Models\Doctor;
use App\Models\OnlineAppointment;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Filament\Facades\Filament;
use Filament\Notifications\Livewire\Notifications;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class WebsiteAppointmentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        Filament::bootCurrentPanel();
    }

    public static function staffRoles(): array
    {
        return [
            'admin' => ['admin'],
            'reception' => ['reception'],
        ];
    }

    #[DataProvider('staffRoles')]
    public function test_website_submission_appears_in_the_staff_inbox_for_a_future_date(string $role): void
    {
        $this->travelTo('2026-10-15 10:00:00');

        $response = $this->postJson('/website-appointment', [
            'fullName' => 'Website Test Patient',
            'phone' => '0770000000',
            'service' => 'General Ayurveda OPD Consultation',
            'preferredDate' => '2026-10-20',
            'preferredTime' => 'Afternoon',
            'contactMethod' => 'WhatsApp',
            'notes' => 'Please confirm the requested time.',
        ])->assertCreated();

        $appointment = OnlineAppointment::findOrFail($response->json('appointment_id'));
        $this->assertSame($response->json('booking_number'), $appointment->booking_number);
        $this->assertSame('Website', $appointment->source);
        $this->assertSame('pending', $appointment->status);
        $this->assertNull($appointment->doctor_id);
        $this->assertSame('2026-10-20 13:00:00', $appointment->appointment_date->format('Y-m-d H:i:s'));
        $this->assertStringContainsString('General Ayurveda OPD Consultation', $appointment->notes);

        $this->actingAs(User::factory()->withRole($role)->create(['is_active' => true]));

        Livewire::test(ListOnlineAppointments::class)
            ->assertSet('activeTab', 'website_requests')
            ->assertCanSeeTableRecords([$appointment])
            ->assertSeeText('Pending Website Requests');

        $this->get('/admin/online-appointments')
            ->assertOk()
            ->assertSeeText('Website Test Patient')
            ->assertSee('Pending website appointment requests');
    }

    public function test_navigation_counts_only_pending_website_requests_and_clears_after_review(): void
    {
        $this->travelTo('2026-10-15 10:00:00');
        $this->actingAs(User::factory()->withRole('admin')->create(['is_active' => true]));

        foreach ([['Website', 'pending'], ['Website', 'confirmed'], ['Reception', 'pending']] as $index => [$source, $status]) {
            OnlineAppointment::create([
                'booking_number' => 'TEST-'.$index,
                'full_name' => 'Test Patient '.$index,
                'nic_or_passport' => 'TEST-NIC-'.$index,
                'phone_number' => '0770000000',
                'patient_type' => 'Local',
                'doctor_id' => null,
                'appointment_date' => '2026-10-20 09:00:00',
                'source' => $source,
                'status' => $status,
            ]);
        }

        $this->assertSame('1', OnlineAppointmentResource::getNavigationBadge());

        OnlineAppointment::where('booking_number', 'TEST-0')->firstOrFail()->update(['status' => 'rejected']);

        $this->assertNull(OnlineAppointmentResource::getNavigationBadge());
    }

    public function test_doctors_do_not_see_unassigned_website_requests_or_their_navigation_count(): void
    {
        $this->travelTo('2026-10-15 10:00:00');
        $response = $this->postJson('/website-appointment', [
            'fullName' => 'Unassigned Website Patient',
            'phone' => '0770000000',
            'service' => 'General Ayurveda OPD Consultation',
            'preferredDate' => '2026-10-20',
        ])->assertCreated();
        $appointment = OnlineAppointment::findOrFail($response->json('appointment_id'));
        $doctor = Doctor::create([
            'name' => 'Test Doctor',
            'email' => 'doctor@example.test',
            'phone_number' => '0770000000',
            'is_active' => true,
            'availability_status' => 'available',
        ]);
        $this->actingAs(User::factory()->withRole('doctor')->create([
            'email' => $doctor->email,
            'is_active' => true,
        ]));

        Livewire::test(ListOnlineAppointments::class)
            ->assertCanNotSeeTableRecords([$appointment])
            ->assertDontSeeText('Website Requests');

        $this->assertNull(OnlineAppointmentResource::getNavigationBadge());
    }

    public static function confirmationPhoneNumbers(): array
    {
        return [
            'WhatsApp available' => ['0713423173', true],
            'invalid number' => ['invalid', false],
        ];
    }

    #[DataProvider('confirmationPhoneNumbers')]
    public function test_confirming_a_website_request_offers_whatsapp_only_for_a_valid_booking_number(string $phoneNumber, bool $hasWhatsApp): void
    {
        $this->travelTo('2026-10-15 10:00:00');
        $this->actingAs(User::factory()->withRole('admin')->create(['is_active' => true]));
        $doctor = Doctor::create([
            'name' => 'Dr. Confirmation Test',
            'phone_number' => '0770000000',
            'is_active' => true,
            'availability_status' => 'available',
            'local_fee' => 1500,
        ]);
        $appointment = OnlineAppointment::create([
            'booking_number' => 'WEB-CONFIRM-001',
            'full_name' => 'Confirmation Test Patient',
            'nic_or_passport' => 'WEBSITE-CONFIRM-001',
            'phone_number' => $phoneNumber,
            'patient_type' => 'Local',
            'doctor_id' => null,
            'appointment_date' => '2026-10-20 13:00:00',
            'source' => 'Website',
            'status' => 'pending',
        ]);

        Livewire::test(ListOnlineAppointments::class)
            ->callTableAction('confirm', $appointment, data: [
                'doctor_id' => $doctor->id,
                'facility_service_fee' => 300,
            ])
            ->assertHasNoTableActionErrors();

        $appointment->refresh();
        $this->assertSame('confirmed', $appointment->status);
        $this->assertSame($doctor->id, $appointment->doctor_id);
        $this->assertNotNull($appointment->invoice_number);

        $notification = Livewire::test(Notifications::class)->assertSeeText('Appointment confirmed');
        $dailyAppointments = Livewire::test(ListOnlineAppointments::class)
            ->set('activeTab', 'daily')
            ->set('selectedDate', '2026-10-20');

        if ($hasWhatsApp) {
            $notification->assertSeeText('Send WhatsApp Confirmation')
                ->assertSee('https://wa.me/94713423173?text=');
            $dailyAppointments->assertTableActionVisible('whatsAppConfirmation', $appointment);
        } else {
            $notification->assertDontSeeText('Send WhatsApp Confirmation');
            $dailyAppointments->assertTableActionHidden('whatsAppConfirmation', $appointment);
        }
    }
}
