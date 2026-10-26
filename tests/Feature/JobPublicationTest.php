<?php

namespace Tests\Feature;

use App\Jobs\SendJobPublishedNotification;
use App\Jobs\StoreBroadcastNotifications;
use App\Models\Company;
use App\Models\ContractType;
use App\Models\Job;
use App\Models\Recruiter;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

class JobPublicationTest extends TestCase
{
    use RefreshDatabase;

    private User $recruiter;
    private Company $company;
    private ContractType $contractType;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();

        $this->company = Company::create([
            'name' => 'Entreprise test',
            'email' => 'jobs@example.test',
            'sector' => 'Services',
            'city' => 'Douala',
            'status' => 'verified',
        ]);
        $this->recruiter = User::factory()->recruiter()->create([
            'current_company_id' => $this->company->id,
            'fcm_token' => 'recruiter-token',
        ]);
        Recruiter::create([
            'company_id' => $this->company->id,
            'user_id' => $this->recruiter->id,
            'position' => 'Manager',
            'can_publish' => true,
        ]);
        $this->contractType = ContractType::create(['name' => 'CDI', 'slug' => 'cdi']);
    }

    private function payload(): array
    {
        return [
            'title' => 'Développeur Laravel',
            'description' => 'Rejoignez notre équipe.',
            'visibility' => 'national',
            'contract_type_id' => $this->contractType->id,
            'experience_level' => 'junior',
        ];
    }

    private function job(array $attributes = []): Job
    {
        return Job::create(array_merge($this->payload(), [
            'company_id' => $this->company->id,
            'posted_by' => $this->recruiter->id,
        ], $attributes));
    }

    public function test_recruiter_offer_is_immediately_published_visible_and_queued_for_push(): void
    {
        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->postJson('/api/jobs', $this->payload(), ['Accept-Language' => 'fr'])
            ->assertCreated()
            ->assertJsonPath('message', 'Offre publiée avec succès.')
            ->assertJsonPath('data.status', 'published');

        $this->assertNotNull($response->json('data.published_at'));
        $this->getJson('/api/jobs')->assertOk()
            ->assertJsonPath('data.0.id', $response->json('data.id'));
        Queue::assertPushedOn('notifications', SendJobPublishedNotification::class);
        Queue::assertPushed(SendJobPublishedNotification::class, fn ($notification) => $notification->afterCommit === true);
        Queue::assertPushed(SendJobPublishedNotification::class, 1);
    }

    public function test_legacy_pending_status_is_published_automatically(): void
    {
        $job = $this->job(['status' => 'pending']);

        $this->assertSame('published', $job->fresh()->status);
        $this->assertNotNull($job->published_at);
        Queue::assertPushed(SendJobPublishedNotification::class, 1);
    }

    public function test_editing_a_published_offer_does_not_repeat_the_push(): void
    {
        $job = $this->job();
        $publishedAt = $job->published_at;
        $job->update(['title' => 'Titre corrigé']);
        $job->refresh()->update(['description' => 'Description corrigée']);

        $this->assertTrue($publishedAt->equalTo($job->published_at));
        Queue::assertPushed(SendJobPublishedNotification::class, 1);
    }

    public function test_explicit_drafts_and_closed_offers_are_not_announced(): void
    {
        foreach (['draft', 'closed', 'expired'] as $status) {
            $job = $this->job(['status' => $status]);
            $this->assertSame($status, $job->status);
            $this->assertNull($job->published_at);
        }

        Queue::assertNothingPushed();
    }

    public function test_admin_publication_and_repeated_publish_only_queue_one_push(): void
    {
        $job = $this->job(['status' => 'draft']);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->patch(route('admin.jobs.publish', $job))->assertRedirect();
        $this->patch(route('admin.jobs.publish', $job))->assertRedirect();

        $this->assertSame('published', $job->fresh()->status);
        Queue::assertPushed(SendJobPublishedNotification::class, 1);
    }

    public function test_admin_creation_with_legacy_pending_status_queues_one_push(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->post(route('admin.jobs.store'), array_merge($this->payload(), [
            'company_id' => $this->company->id,
            'status' => 'pending',
        ]))->assertSessionHasNoErrors()->assertRedirect();

        $this->assertSame('published', Job::sole()->status);
        Queue::assertPushed(SendJobPublishedNotification::class, 1);
    }

    public function test_one_topic_push_is_sent_and_history_covers_all_roles_without_token_filtering(): void
    {
        $candidate = User::factory()->candidate()->unverified()->create(['fcm_token' => 'candidate-token']);
        $admin = User::factory()->admin()->create(['fcm_token' => 'admin-token']);
        $withoutToken = User::factory()->create(['fcm_token' => null]);
        User::factory()->create(['fcm_token' => '']);
        $deleted = User::factory()->create(['fcm_token' => 'deleted-token']);
        $deleted->delete();
        $job = $this->job();

        $firebase = Mockery::mock(FirebaseNotificationService::class);
        $firebase->shouldReceive('sendToTopic')->once()->withArgs(
            function ($topic, $title, $message, $data) use ($job) {
                $this->assertSame('all_users', $topic);
                $this->assertSame('job_published', $data['type']);
                $this->assertSame((string) $job->id, $data['job_id']);

                return true;
            }
        )->andReturn(true);

        (new SendJobPublishedNotification($job))->handle($firebase);
        Queue::assertPushed(StoreBroadcastNotifications::class, 1);
        $history = Queue::pushed(StoreBroadcastNotifications::class)->first();
        $history->handle();
        $history->handle();

        foreach ([$candidate, $admin, $this->recruiter, $withoutToken] as $user) {
            $this->assertDatabaseHas('notifications', [
                'notifiable_id' => $user->id,
                'type' => 'job_published',
            ]);
        }
        $this->assertDatabaseMissing('notifications', ['notifiable_id' => $deleted->id]);
        $this->assertDatabaseCount('notifications', 5);
    }

    public function test_withdrawn_offer_is_not_announced_by_a_waiting_worker(): void
    {
        $job = $this->job();
        $notification = new SendJobPublishedNotification($job);
        $job->update(['status' => 'closed']);

        $service = Mockery::mock(FirebaseNotificationService::class);
        $service->shouldNotReceive('sendToTopic');
        $notification->handle($service);
        $job->delete();
        $notification->handle($service);
    }

    public function test_firebase_rejection_fails_the_job_so_the_queue_can_retry(): void
    {
        $job = $this->job();
        $firebase = Mockery::mock(FirebaseNotificationService::class);
        $firebase->shouldReceive('sendToTopic')->once()->andReturn(false);

        $this->expectException(\RuntimeException::class);
        try {
            (new SendJobPublishedNotification($job))->handle($firebase);
        } finally {
            Queue::assertNotPushed(StoreBroadcastNotifications::class);
        }
    }

    public function test_migration_releases_existing_pending_offers_only_once(): void
    {
        $pending = $this->job(['status' => 'draft']);
        DB::table('jobs')->where('id', $pending->id)->update(['status' => 'pending']);
        $draft = $this->job(['status' => 'draft']);
        $closed = $this->job(['status' => 'closed']);
        $published = $this->job();
        Queue::fake();

        $migration = require database_path('migrations/2026_10_26_100000_publish_pending_job_offers.php');
        $migration->up();
        $migration->up();

        $this->assertSame('published', $pending->fresh()->status);
        $this->assertNotNull($pending->fresh()->published_at);
        $this->assertSame('draft', $draft->fresh()->status);
        $this->assertSame('closed', $closed->fresh()->status);
        $this->assertSame('published', $published->fresh()->status);
        Queue::assertPushed(SendJobPublishedNotification::class, 1);
    }
}
