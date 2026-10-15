<?php

namespace Tests\Feature;

use App\Jobs\SendWebsiteAppointmentNotification;
use App\Mail\WebsiteAppointmentRequestMail;
use App\Models\OnlineAppointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class WebsiteAppointmentNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_submission_prepares_a_message_to_the_centre_and_duplicate_retries_dispatch_one_email(): void
    {
        $this->travelTo('2026-10-15 10:00:00');
        Bus::fake([SendWebsiteAppointmentNotification::class]);

        $first = $this->postJson('/website-appointment', [
            ...$this->payload(),
            'notification_email' => 'untrusted@example.test',
            'notification_whatsapp' => '94770000000',
        ])->assertCreated();
        $second = $this->postJson('/website-appointment', $this->payload())->assertCreated();

        $this->assertSame($first->json('booking_number'), $second->json('booking_number'));
        $this->assertDatabaseCount('online_appointments', 1);
        $url = $first->json('whatsapp_url');
        $this->assertSame('https', parse_url($url, PHP_URL_SCHEME));
        $this->assertSame('wa.me', parse_url($url, PHP_URL_HOST));
        $this->assertSame('/94713423173', parse_url($url, PHP_URL_PATH));
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
        $this->assertStringContainsString('Booking number: '.$first->json('booking_number'), $query['text']);
        $this->assertStringContainsString('Name: Website Notification Test', $query['text']);
        $this->assertStringContainsString('Contact number: 0770000000', $query['text']);
        $this->assertStringContainsString('Preferred date: 20 Oct 2026', $query['text']);
        $this->assertStringNotContainsString('Private consultation note', $query['text']);
        Bus::assertDispatchedAfterResponseTimes(SendWebsiteAppointmentNotification::class, 1);
        Bus::assertDispatchedAfterResponse(SendWebsiteAppointmentNotification::class, fn ($job): bool => $job->appointmentId === $first->json('appointment_id'));
    }

    public function test_invalid_submission_does_not_save_or_dispatch_a_notification(): void
    {
        Bus::fake([SendWebsiteAppointmentNotification::class]);

        $this->postJson('/website-appointment', [])->assertUnprocessable();

        $this->assertDatabaseCount('online_appointments', 0);
        Bus::assertDispatchedAfterResponseTimes(SendWebsiteAppointmentNotification::class, 0);
    }

    public function test_configured_email_delivery_sends_the_saved_request_to_the_centre(): void
    {
        $this->travelTo('2026-10-15 10:00:00');
        config(['mail.enabled' => true, 'mail.default' => 'smtp']);
        Mail::fake();

        $response = $this->postJson('/website-appointment', $this->payload())->assertCreated();

        $this->assertDatabaseHas('online_appointments', ['id' => $response->json('appointment_id'), 'status' => 'pending']);
        Mail::assertSent(WebsiteAppointmentRequestMail::class, function (WebsiteAppointmentRequestMail $mail) use ($response): bool {
            return $mail->hasTo('info@devyaceylon.lk')
                && $mail->appointment->id === $response->json('appointment_id');
        });
        Mail::assertSentCount(1);
    }

    public function test_disabled_email_does_not_send_a_notification_or_prevent_booking(): void
    {
        $this->travelTo('2026-10-15 10:00:00');
        config(['mail.enabled' => false]);
        Mail::fake();

        $this->postJson('/website-appointment', $this->payload())->assertCreated();

        $this->assertDatabaseCount('online_appointments', 1);
        Mail::assertNothingOutgoing();
    }

    public function test_a_mail_failure_does_not_turn_a_saved_booking_into_a_failed_submission(): void
    {
        $this->travelTo('2026-10-15 10:00:00');
        config(['mail.enabled' => true, 'mail.default' => 'smtp']);
        Mail::shouldReceive('to')->once()->with('info@devyaceylon.lk')->andThrow(new RuntimeException('Mail unavailable'));
        Log::spy();

        $response = $this->postJson('/website-appointment', $this->payload())->assertCreated();

        $this->assertDatabaseCount('online_appointments', 1);
        Log::shouldHaveReceived('warning')->once()->with('Website appointment email notification failed.', [
            'appointment_id' => $response->json('appointment_id'),
            'exception' => RuntimeException::class,
        ]);
    }

    public function test_email_content_contains_request_details_and_escapes_patient_input(): void
    {
        $appointment = new OnlineAppointment([
            'booking_number' => 'WEB-MAIL-001',
            'full_name' => '<script>alert("patient")</script>',
            'phone_number' => '0770000000',
            'appointment_date' => '2026-10-20 13:00:00',
            'notes' => 'Requested service: General Ayurveda OPD Consultation'.PHP_EOL.'Notes: <img src=x onerror=alert(1)>',
        ]);

        $html = (new WebsiteAppointmentRequestMail($appointment))->render();

        $this->assertStringContainsString('WEB-MAIL-001', $html);
        $this->assertStringContainsString('20 Oct 2026', $html);
        $this->assertStringContainsString('General Ayurveda OPD Consultation', $html);
        $this->assertStringContainsString('tab=website_requests', $html);
        $this->assertStringNotContainsString('<script>alert("patient")</script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringNotContainsString('<img src=x onerror=alert(1)>', $html);
        $this->assertStringContainsString('&lt;img', $html);
    }

    public function test_html_form_submission_opens_whatsapp_after_saving_the_request(): void
    {
        $this->travelTo('2026-10-15 10:00:00');
        Bus::fake([SendWebsiteAppointmentNotification::class]);

        $response = $this->post('/website-appointment', $this->payload());

        $response->assertRedirect();
        $this->assertStringStartsWith('https://wa.me/94713423173?text=', $response->headers->get('Location'));
        $this->assertDatabaseCount('online_appointments', 1);
    }

    private function payload(): array
    {
        return [
            'fullName' => 'Website Notification Test',
            'phone' => '0770000000',
            'service' => 'General Ayurveda OPD Consultation',
            'preferredDate' => '2026-10-20',
            'preferredTime' => 'Afternoon',
            'contactMethod' => 'WhatsApp',
            'notes' => 'Private consultation note',
        ];
    }
}
