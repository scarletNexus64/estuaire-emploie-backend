<?php

namespace Tests\Feature;

use App\Jobs\SendCvProfileRequestNotification;
use App\Jobs\StoreBroadcastNotifications;
use App\Models\Company;
use App\Models\CvProfileRequest;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

class CvProfileRequestNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $recruiter;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        $company = Company::create([
            'name' => 'Entreprise test',
            'email' => 'profiles@example.test',
            'sector' => 'Services',
            'status' => 'verified',
        ]);
        $this->recruiter = User::factory()->recruiter()->create([
            'current_company_id' => $company->id,
        ]);
    }

    private function profileRequest(array $attributes = []): CvProfileRequest
    {
        return CvProfileRequest::create(array_merge([
            'user_id' => $this->recruiter->id,
            'company_id' => $this->recruiter->current_company_id,
            'title' => 'Développeur Laravel',
            'description' => 'Recherche un profil expérimenté en PHP.',
            'status' => 'open',
        ], $attributes));
    }

    public function test_publication_queues_one_immediate_broadcast(): void
    {
        $this->actingAs($this->recruiter, 'sanctum')->postJson('/api/cv-profile-requests', [
            'title' => 'Développeur Laravel',
            'description' => 'Recherche un profil expérimenté en PHP.',
        ])->assertCreated()->assertJsonPath('notification_queued', true);

        Queue::assertPushedOn('notifications', SendCvProfileRequestNotification::class);
        Queue::assertPushed(SendCvProfileRequestNotification::class, fn ($job) => $job->delay === null && $job->afterCommit === true);
        Queue::assertPushed(SendCvProfileRequestNotification::class, 1);
    }

    public function test_broadcast_uses_one_topic_and_does_not_schedule_delayed_waves(): void
    {
        $request = $this->profileRequest();
        $firebase = Mockery::mock(FirebaseNotificationService::class);
        $firebase->shouldReceive('sendToTopic')->once()->withArgs(
            fn ($topic, $title, $body, $data) => $topic === 'all_users'
                && $data['type'] === 'cv_profile_request'
                && $data['request_id'] === (string) $request->id
        )->andReturn(true);

        (new SendCvProfileRequestNotification($request->id))->handle($firebase);
        // Un ancien job de vague déjà en file ne rediffuse pas une demande terminée.
        (new SendCvProfileRequestNotification($request->id, 1))->handle($firebase);

        $this->assertNotNull($request->fresh()->notified_at);
        Queue::assertNotPushed(SendCvProfileRequestNotification::class);
        Queue::assertPushed(StoreBroadcastNotifications::class, fn ($job) => $job->roles === [] && $job->excludeUserId === null);
        Queue::assertPushed(StoreBroadcastNotifications::class, 1);
    }

    public function test_rejected_push_stays_unnotified_and_can_be_retried(): void
    {
        $request = $this->profileRequest();
        $firebase = Mockery::mock(FirebaseNotificationService::class);
        $firebase->shouldReceive('sendToTopic')->twice()->andReturn(false, true);
        $job = new SendCvProfileRequestNotification($request->id);

        try {
            $job->handle($firebase);
            $this->fail('Un échec FCM doit déclencher une nouvelle tentative de la queue.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('Firebase', $e->getMessage());
        }

        $this->assertNull($request->fresh()->notified_at);
        Queue::assertNothingPushed();
        $job->handle($firebase);
        $this->assertNotNull($request->fresh()->notified_at);
        Queue::assertPushed(StoreBroadcastNotifications::class, 1);
    }

    public function test_closed_or_deleted_requests_are_not_broadcast(): void
    {
        $request = $this->profileRequest(['status' => 'closed']);
        $firebase = Mockery::mock(FirebaseNotificationService::class);
        $firebase->shouldNotReceive('sendToTopic');
        $job = new SendCvProfileRequestNotification($request->id);
        $job->handle($firebase);
        $request->delete();
        $job->handle($firebase);

        Queue::assertNothingPushed();
    }

    public function test_queue_failure_is_not_reported_as_successful_notification_scheduling(): void
    {
        Queue::shouldReceive('connection')->once()->andThrow(new \RuntimeException('Queue unavailable'));

        $this->actingAs($this->recruiter, 'sanctum')->postJson('/api/cv-profile-requests', [
            'title' => 'Développeur Laravel',
            'description' => 'Recherche un profil expérimenté en PHP.',
        ])->assertCreated()->assertJsonPath('notification_queued', false);

        $this->assertDatabaseCount('cv_profile_requests', 1);
        $this->assertNull(CvProfileRequest::sole()->notified_at);
    }
}
