<?php

namespace Tests\Feature;

use App\Jobs\SendCompanyRecoveryDelivery;
use App\Jobs\SendServiceRequestNotification;
use App\Jobs\StoreBroadcastNotifications;
use App\Models\Company;
use App\Models\CompanyRecoveryDelivery;
use App\Models\CompanyRecoveryRequest;
use App\Models\DigitalizationRequest;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

class PushDeliveryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    private function serviceRequest(array $attributes = []): DigitalizationRequest
    {
        return DigitalizationRequest::create($attributes + [
            'user_id' => User::factory()->create()->id,
            'category' => 'professional_service', 'project_name' => 'Besoin d’un peintre',
            'description' => 'Peindre les murs de notre atelier.', 'city' => 'Douala',
            'channels' => ['estuaire'], 'status' => 'pending',
        ]);
    }

    public function test_service_publication_queues_an_immediate_notification_after_commit(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum')->postJson('/api/digitalization-requests', [
            'category' => 'professional_service', 'project_name' => 'Besoin d’un comptable',
            'description' => 'Accompagner notre petite entreprise.', 'channels' => ['estuaire'],
        ])->assertCreated()->assertJsonPath('notification_queued', true);
        Queue::assertPushedOn('notifications', SendServiceRequestNotification::class);
        Queue::assertPushed(SendServiceRequestNotification::class, fn ($job) => $job->delay === null && $job->afterCommit === true);
        Queue::assertPushed(SendServiceRequestNotification::class, 1);
    }

    public function test_service_queue_failure_is_not_reported_as_success(): void
    {
        Queue::shouldReceive('connection')->once()->andThrow(new \RuntimeException('Queue unavailable'));
        $this->actingAs(User::factory()->create(), 'sanctum')->postJson('/api/digitalization-requests', [
            'project_name' => 'Besoin d’un comptable', 'description' => 'Accompagner notre petite entreprise.',
            'channels' => ['estuaire'],
        ])->assertCreated()->assertJsonPath('notification_queued', false);
        $this->assertNull(DigitalizationRequest::sole()->notified_at);
    }

    public function test_service_broadcast_uses_global_topic_and_retries_refused_push(): void
    {
        $request = $this->serviceRequest();
        $firebase = Mockery::mock(FirebaseNotificationService::class);
        $firebase->shouldReceive('sendToTopic')->twice()->withArgs(fn ($topic, $title, $body, $data) =>
            $topic === 'all_users' && $data['type'] === 'service_request'
            && $data['request_id'] === (string) $request->id
            && $data['requester_id'] === (string) $request->user_id
        )->andReturn(false, true);
        $job = new SendServiceRequestNotification($request->id);
        try {
            $job->handle($firebase);
            $this->fail('Un refus Firebase doit déclencher une nouvelle tentative.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('Firebase', $e->getMessage());
        }
        $this->assertNull($request->fresh()->notified_at);
        $job->handle($firebase);
        $this->assertNotNull($request->fresh()->notified_at);
        (new SendServiceRequestNotification($request->id, 2))->handle($firebase);
        Queue::assertNotPushed(SendServiceRequestNotification::class);
        Queue::assertPushed(StoreBroadcastNotifications::class, fn ($history) => $history->roles === [] && $history->excludeUserId === null);
        // L'auteur reçoit aussi l'historique et un retry ne le duplique pas.
        $history = Queue::pushed(StoreBroadcastNotifications::class)->first();
        $history->handle();
        $history->handle();
        $this->assertSame(1, DB::table('notifications')->where('notifiable_id', $request->user_id)->where('type', 'service_request')->count());
    }

    public function test_rejected_deleted_and_expired_service_requests_are_not_broadcast(): void
    {
        $firebase = Mockery::mock(FirebaseNotificationService::class);
        $firebase->shouldNotReceive('sendToTopic');
        $rejected = $this->serviceRequest(['status' => 'rejected']);
        $deleted = $this->serviceRequest();
        $deleted->delete();
        $old = $this->serviceRequest();
        $old->forceFill(['created_at' => now()->subDays(31)])->saveQuietly();
        foreach ([$rejected, $deleted, $old] as $request) {
            (new SendServiceRequestNotification($request->id))->handle($firebase);
        }
        Queue::assertNothingPushed();
    }

    private function recoveryDelivery(?string $token = 'test-device'): CompanyRecoveryDelivery
    {
        $user = User::factory()->create(['fcm_token' => $token]);
        $company = Company::create(['name' => 'Atelier', 'email' => 'atelier@example.test', 'status' => 'verified']);
        $request = CompanyRecoveryRequest::create([
            'company_id' => $company->id, 'requester_user_id' => $user->id,
            'mode' => 'attach', 'name' => 'Alice', 'phone' => '690000000',
            'position' => 'Gérante', 'explanation' => 'Je gère cet atelier.', 'status' => 'approved',
        ]);
        return CompanyRecoveryDelivery::create([
            'request_id' => $request->id, 'channel' => 'push',
            'payload' => ['user_id' => $user->id, 'title' => 'Approuvée', 'message' => 'Entreprise récupérée', 'status' => 'approved'],
        ]);
    }

    private function assertDeliveryPending(CompanyRecoveryDelivery $delivery): void
    {
        try {
            (new SendCompanyRecoveryDelivery($delivery->id))->handle();
            $this->fail('Un envoi non accepté doit rester en attente.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('Échec de livraison', $e->getMessage());
        }
        $delivery->refresh();
        $this->assertNull($delivery->sent_at);
        $this->assertNotNull($delivery->payload);
        $this->assertNotNull($delivery->last_error);
    }

    public function test_recovery_without_a_device_is_not_marked_sent(): void
    {
        $this->mock(FirebaseNotificationService::class)->shouldNotReceive('sendToToken');
        $this->assertDeliveryPending($this->recoveryDelivery(null));
    }

    public function test_disabled_firebase_keeps_recovery_pending_until_accepted(): void
    {
        $delivery = $this->recoveryDelivery();
        $this->mock(FirebaseNotificationService::class)->shouldReceive('sendToToken')->twice()
            ->andReturn(null, ['name' => 'projects/test/messages/accepted']);
        $this->assertDeliveryPending($delivery);
        $job = new SendCompanyRecoveryDelivery($delivery->id);
        $job->handle();
        $job->handle();
        $delivery->refresh();
        $this->assertNotNull($delivery->sent_at);
        $this->assertNull($delivery->payload);
        $this->assertNull($delivery->last_error);
    }

    public function test_recovery_transport_error_remains_retryable_without_exposing_details(): void
    {
        $delivery = $this->recoveryDelivery();
        $this->mock(FirebaseNotificationService::class)->shouldReceive('sendToToken')->once()
            ->andThrow(new \RuntimeException('invalid_grant secret details'));
        $this->assertDeliveryPending($delivery);
        $this->assertStringNotContainsString('secret', $delivery->fresh()->last_error);
    }
}
