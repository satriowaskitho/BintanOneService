<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Visitor;
use App\Models\ServiceType;
use App\Models\Queue;
use App\Models\Appointment;
use App\Mail\QueueCreatedMail;
use App\Mail\AppointmentCreatedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Tests\TestCase;

class HybridServiceRefactorTest extends TestCase
{
    use RefreshDatabase;

    private $visitor;
    private $serviceType;
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->visitor = Visitor::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '08123456789',
            'gender' => 'Laki-laki',
            'institution' => 'BPS Bintan',
            'face_data' => array_fill(0, 128, 0.1) // Flat fake descriptor
        ]);

        $this->serviceType = ServiceType::create([
            'name' => 'Layanan Statistik',
            'code' => 'A'
        ]);

        $this->admin = User::factory()->create();
    }

    public function test_kiosk_options_redirects_active_queue_to_signed_tracking_page()
    {
        $queue = Queue::create([
            'visitor_id' => $this->visitor->id,
            'service_type_id' => $this->serviceType->id,
            'queue_number' => 'A-001',
            'status' => 'waiting',
            'token' => 'abcde12345'
        ]);

        $response = $this->get(route('kiosk.options', $this->visitor->id));

        $response->assertRedirect();
        $this->assertTrue(str_contains($response->headers->get('Location'), '/queue/abcde12345'));
    }

    public function test_kiosk_options_displays_checkin_button_for_todays_scheduled_appointment()
    {
        $appointment = Appointment::create([
            'visitor_id' => $this->visitor->id,
            'service_type_id' => $this->serviceType->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'time' => '10:00',
            'purpose' => 'Konsultasi Data Inflasi',
            'required_documents' => 'KTP',
            'email' => 'john@example.com',
            'status' => 'scheduled',
            'token' => 'apptoken12'
        ]);

        $response = $this->get(route('kiosk.options', $this->visitor->id));

        $response->assertStatus(200);
        $response->assertSee('Check-In Sekarang');
    }

    public function test_visitor_cannot_have_more_than_one_active_queue_at_a_time()
    {
        Queue::create([
            'visitor_id' => $this->visitor->id,
            'service_type_id' => $this->serviceType->id,
            'queue_number' => 'A-001',
            'status' => 'waiting',
            'token' => 't1'
        ]);

        $response = $this->post(route('kiosk.queue.generate'), [
            'visitor_id' => $this->visitor->id,
            'service_type_id' => $this->serviceType->id,
            'purpose' => 'Testing'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('queues', 1);
    }

    public function test_queue_generation_enforces_one_minute_anti_spam_cooldown()
    {
        $queue = Queue::create([
            'visitor_id' => $this->visitor->id,
            'service_type_id' => $this->serviceType->id,
            'queue_number' => 'A-001',
            'status' => 'done', // Done status but created just now
            'token' => 't1',
            'created_at' => now()
        ]);

        $response = $this->post(route('kiosk.queue.generate'), [
            'visitor_id' => $this->visitor->id,
            'service_type_id' => $this->serviceType->id,
            'purpose' => 'Testing Cooldown'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('queues', 1);
    }

    public function test_appointment_booking_validates_future_dates_and_past_times_on_current_day()
    {
        // Past date should fail validation
        $response = $this->post(route('kiosk.appointment.store', $this->visitor->id), [
            'visitor_id' => $this->visitor->id,
            'service_type_id' => $this->serviceType->id,
            'date' => Carbon::yesterday()->format('Y-m-d'),
            'time' => '11:00',
            'purpose' => 'Konsultasi Singkat',
            'email' => 'john@example.com'
        ]);

        $response->assertSessionHasErrors(['date']);
        $this->assertDatabaseCount('appointments', 0);

        // Past time on today should fail validation
        $response2 = $this->post(route('kiosk.appointment.store', $this->visitor->id), [
            'visitor_id' => $this->visitor->id,
            'service_type_id' => $this->serviceType->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'time' => Carbon::now()->subHours(2)->format('H:i'), // past time
            'purpose' => 'Konsultasi Singkat',
            'email' => 'john@example.com'
        ]);

        $response2->assertSessionHasErrors(['time']);
        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_appointment_booking_enforces_slot_capacity_of_two_per_slot()
    {
        // Book 2 appointments for the same date and time
        for ($i = 0; $i < 2; $i++) {
            $otherVisitor = Visitor::create([
                'name' => "Visitor $i",
                'gender' => 'Laki-laki',
                'face_data' => array_fill(0, 128, 0.2)
            ]);

            Appointment::create([
                'visitor_id' => $otherVisitor->id,
                'service_type_id' => $this->serviceType->id,
                'date' => Carbon::tomorrow()->format('Y-m-d'),
                'time' => '10:00',
                'purpose' => 'Konsultasi Data',
                'required_documents' => 'KTP',
                'email' => 'other@example.com',
                'status' => 'scheduled',
                'token' => 'token_' . $i
            ]);
        }

        // 3rd booking on the same slot should fail
        $response = $this->post(route('kiosk.appointment.store', $this->visitor->id), [
            'visitor_id' => $this->visitor->id,
            'service_type_id' => $this->serviceType->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'time' => '10:00',
            'purpose' => 'Konsultasi Ke-3',
            'required_documents' => ['KTP'],
            'email' => 'john@example.com'
        ]);

        $response->assertSessionHasErrors(['time']);
        $this->assertDatabaseCount('appointments', 2);
    }

    public function test_expired_signature_on_tracking_urls_shows_recovery_page()
    {
        $queue = Queue::create([
            'visitor_id' => $this->visitor->id,
            'service_type_id' => $this->serviceType->id,
            'queue_number' => 'A-001',
            'status' => 'waiting',
            'token' => 'abcde12345'
        ]);

        // Attempting to access without signed URL triggers redirect to recovery page
        $response = $this->get(route('queue.track', 'abcde12345'));

        $response->assertRedirect(route('tracking.recovery'));
    }

    public function test_tracking_recovery_supports_token_lookup_and_redirects_to_new_signed_url()
    {
        $queue = Queue::create([
            'visitor_id' => $this->visitor->id,
            'service_type_id' => $this->serviceType->id,
            'queue_number' => 'A-001',
            'status' => 'waiting',
            'token' => 'abcde12345'
        ]);

        $response = $this->post(route('tracking.recovery.submit'), [
            'code' => 'abcde12345',
            'action_type' => 'track'
        ]);

        $response->assertRedirect();
        $location = $response->headers->get('Location');
        $this->assertTrue(str_contains($location, '/queue/abcde12345'));
        $this->assertTrue(str_contains($location, 'signature='));
    }

    public function test_email_resending_enforces_sixty_seconds_cooldown()
    {
        $queue = Queue::create([
            'visitor_id' => $this->visitor->id,
            'service_type_id' => $this->serviceType->id,
            'queue_number' => 'A-001',
            'status' => 'waiting',
            'token' => 'abcde12345',
            'last_email_sent_at' => now()->subSeconds(30) // Only 30 seconds ago
        ]);

        $response = $this->post(route('kiosk.queue.resend-email', 'abcde12345'));

        $response->assertSessionHasErrors(['error']);
    }

    public function test_waiting_time_estimation_uses_linear_formula_and_shows_processing_at_first_position()
    {
        Mail::fake();

        $appointment = Appointment::create([
            'visitor_id' => $this->visitor->id,
            'service_type_id' => $this->serviceType->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'time' => '10:00',
            'purpose' => 'Konsultasi Data',
            'required_documents' => 'KTP',
            'email' => 'john@example.com',
            'status' => 'scheduled',
            'token' => 'apptoken12'
        ]);

        // Check-in creates queue ticket
        $this->post(route('kiosk.appointment.checkin', $appointment->id));

        $queue = Queue::where('queue_source', 'appointment')->first();

        // Signed URL access to prevent signature redirect
        $signedUrl = URL::temporarySignedRoute('queue.track', now()->addHours(24), ['token' => $queue->token]);

        $response = $this->get($signedUrl);
        $response->assertStatus(200);
        $response->assertSee('Sedang diproses'); // Position is 0
    }
}
