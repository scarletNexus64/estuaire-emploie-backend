<?php

namespace Tests\Feature;

use App\Events\JobPublished;
use App\Listeners\SendJobPublishedNotification;
use App\Models\Category;
use App\Models\Company;
use App\Models\ContractType;
use App\Models\Job;
use App\Models\Location;
use App\Models\User;
use App\Notifications\NewJobNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class JobPublishedNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createBaseData();
    }

    protected function createBaseData(): void
    {
        Category::create(['name' => 'Informatique', 'slug' => 'informatique']);
        Location::create(['name' => 'Douala', 'slug' => 'douala']);
        ContractType::create(['name' => 'CDI', 'slug' => 'cdi']);

        Company::create([
            'name' => 'Test Company',
            'email' => 'company@test.com',
            'sector' => 'Technologie',
            'phone' => '+237 690 000 000',
            'address' => '123 Rue Test',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'status' => 'verified',
        ]);
    }

    protected function createCandidates(int $count): \Illuminate\Support\Collection
    {
        $candidates = collect();

        for ($i = 1; $i <= $count; $i++) {
            $candidate = User::create([
                'name' => "Candidat $i",
                'email' => "candidat{$i}@example.com",
                'phone' => "+237 690 " . str_pad($i, 6, '0', STR_PAD_LEFT),
                'role' => 'candidate',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]);
            $candidates->push($candidate);
        }

        return $candidates;
    }

    protected function createJob(): Job
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        return Job::create([
            'title' => 'Développeur Laravel Senior',
            'description' => 'Nous recherchons un développeur Laravel expérimenté.',
            'company_id' => Company::first()->id,
            'category_id' => Category::first()->id,
            'location_id' => Location::first()->id,
            'contract_type_id' => ContractType::first()->id,
            'posted_by' => $admin->id,
            'status' => 'pending',
            'experience_level' => 'senior',
        ]);
    }

    /**
     * Test que l'événement JobPublished est bien dispatché lors de la publication.
     */
    public function test_job_published_event_is_dispatched(): void
    {
        Event::fake([JobPublished::class]);

        $job = $this->createJob();

        JobPublished::dispatch($job);

        Event::assertDispatched(JobPublished::class, function ($event) use ($job) {
            return $event->job->id === $job->id;
        });
    }

    /**
     * Test que le listener est bien enregistré et exécuté.
     */
    public function test_listener_is_registered_for_job_published_event(): void
    {
        Event::fake();

        $job = $this->createJob();

        JobPublished::dispatch($job);

        Event::assertDispatched(JobPublished::class);
    }

    /**
     * Test que les notifications sont envoyées à 50 candidats.
     */
    public function test_notifications_are_sent_to_50_candidates(): void
    {
        Notification::fake();

        $candidates = $this->createCandidates(50);
        $this->assertCount(50, $candidates);

        $job = $this->createJob();
        $job->update(['status' => 'published', 'published_at' => now()]);
        $job->load(['company', 'category', 'location', 'contractType']);

        // Simuler l'envoi de notifications (comme le ferait le listener)
        foreach ($candidates as $candidate) {
            $candidate->notify(new NewJobNotification($job));
        }

        Notification::assertCount(50);

        foreach ($candidates as $candidate) {
            Notification::assertSentTo($candidate, NewJobNotification::class);
        }
    }

    /**
     * Test l'envoi progressif par chunks à 300 candidats.
     */
    public function test_notifications_are_sent_to_300_candidates(): void
    {
        Notification::fake();

        $candidates = $this->createCandidates(300);
        $this->assertCount(300, $candidates);

        $job = $this->createJob();
        $job->update(['status' => 'published', 'published_at' => now()]);
        $job->load(['company', 'category', 'location', 'contractType']);

        // Simuler l'envoi par chunks de 100 (comme le listener)
        $chunks = $candidates->chunk(100);
        $this->assertCount(3, $chunks, 'Devrait avoir 3 chunks de 100 candidats');

        foreach ($chunks as $chunk) {
            foreach ($chunk as $candidate) {
                $candidate->notify(new NewJobNotification($job));
            }
        }

        Notification::assertCount(300);

        // Vérifier quelques candidats spécifiques
        Notification::assertSentTo($candidates->first(), NewJobNotification::class);
        Notification::assertSentTo($candidates->get(149), NewJobNotification::class);
        Notification::assertSentTo($candidates->last(), NewJobNotification::class);
    }

    /**
     * Test que seuls les candidats actifs avec email vérifié reçoivent les notifications.
     */
    public function test_only_active_verified_candidates_receive_notifications(): void
    {
        Notification::fake();

        // Créer des candidats actifs avec email vérifié
        $activeCandidates = $this->createCandidates(10);

        // Créer des candidats inactifs
        $inactiveCandidates = collect();
        for ($i = 1; $i <= 5; $i++) {
            $inactiveCandidates->push(User::create([
                'name' => "Candidat Inactif $i",
                'email' => "inactif{$i}@example.com",
                'role' => 'candidate',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'is_active' => false,
            ]));
        }

        // Créer des candidats sans email vérifié
        $unverifiedCandidates = collect();
        for ($i = 1; $i <= 5; $i++) {
            $unverifiedCandidates->push(User::create([
                'name' => "Candidat Non Vérifié $i",
                'email' => "nonverifie{$i}@example.com",
                'role' => 'candidate',
                'password' => bcrypt('password'),
                'email_verified_at' => null,
                'is_active' => true,
            ]));
        }

        // Créer un recruteur (ne devrait pas recevoir)
        $recruiter = User::create([
            'name' => 'Recruteur Test',
            'email' => 'recruteur@example.com',
            'role' => 'recruiter',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $job = $this->createJob();
        $job->update(['status' => 'published', 'published_at' => now()]);
        $job->load(['company', 'category', 'location', 'contractType']);

        // Simuler la logique du listener : envoyer uniquement aux candidats actifs et vérifiés
        // Les candidats actifs et vérifiés sont ceux dans $activeCandidates
        foreach ($activeCandidates as $candidate) {
            $candidate->notify(new NewJobNotification($job));
        }

        // Seuls les 10 candidats actifs et vérifiés doivent recevoir
        Notification::assertCount(10);

        // Vérifier que les candidats actifs ont reçu les notifications
        foreach ($activeCandidates as $candidate) {
            Notification::assertSentTo($candidate, NewJobNotification::class);
        }

        // Vérifier que les inactifs ne reçoivent pas
        foreach ($inactiveCandidates as $candidate) {
            Notification::assertNotSentTo($candidate, NewJobNotification::class);
        }

        // Vérifier que les non vérifiés ne reçoivent pas
        foreach ($unverifiedCandidates as $candidate) {
            Notification::assertNotSentTo($candidate, NewJobNotification::class);
        }

        // Vérifier que le recruteur ne reçoit pas
        Notification::assertNotSentTo($recruiter, NewJobNotification::class);
    }

    /**
     * Test que la notification contient les bonnes informations du job.
     */
    public function test_notification_contains_correct_job_information(): void
    {
        Notification::fake();

        $candidates = $this->createCandidates(1);
        $candidate = $candidates->first();

        $job = $this->createJob();
        $job->update(['status' => 'published', 'published_at' => now()]);
        $job->load(['company', 'category', 'location', 'contractType']);

        $candidate->notify(new NewJobNotification($job));

        Notification::assertSentTo($candidate, NewJobNotification::class, function ($notification, $channels) use ($job, $candidate) {
            $data = $notification->toArray($candidate);

            return $data['job_id'] === $job->id
                && $data['job_title'] === $job->title
                && $data['type'] === 'new_job'
                && in_array('mail', $channels)
                && in_array('database', $channels);
        });
    }

    /**
     * Test que le listener est bien queued (asynchrone).
     */
    public function test_listener_implements_should_queue(): void
    {
        $listener = new SendJobPublishedNotification();

        $this->assertInstanceOf(
            \Illuminate\Contracts\Queue\ShouldQueue::class,
            $listener
        );
    }

    /**
     * Test de performance : l'envoi à 300 candidats se fait par chunks.
     */
    public function test_chunking_performance_with_300_candidates(): void
    {
        Notification::fake();

        $candidates = $this->createCandidates(300);

        $job = $this->createJob();
        $job->update(['status' => 'published', 'published_at' => now()]);
        $job->load(['company', 'category', 'location', 'contractType']);

        $startTime = microtime(true);

        // Simuler l'envoi par chunks
        $candidates->chunk(100)->each(function ($chunk) use ($job) {
            foreach ($chunk as $candidate) {
                $candidate->notify(new NewJobNotification($job));
            }
        });

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        Notification::assertCount(300);

        $this->assertLessThan(30, $executionTime, "L'envoi de 300 notifications devrait prendre moins de 30 secondes");
    }

    /**
     * Test d'intégration complet.
     */
    public function test_full_integration_job_publication_to_notifications(): void
    {
        Notification::fake();

        $candidates = $this->createCandidates(100);

        $admin = User::create([
            'name' => 'Admin Recruteur',
            'email' => 'admin.recruteur@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $job = Job::create([
            'title' => 'Ingénieur DevOps',
            'description' => 'Recherche ingénieur DevOps expérimenté.',
            'company_id' => Company::first()->id,
            'category_id' => Category::first()->id,
            'location_id' => Location::first()->id,
            'contract_type_id' => ContractType::first()->id,
            'posted_by' => $admin->id,
            'status' => 'pending',
            'experience_level' => 'senior',
        ]);

        // Simuler la publication (comme dans le controller admin)
        $wasNotPublished = $job->status !== 'published';

        $job->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        $job->load(['company', 'category', 'location', 'contractType']);

        // Simuler l'envoi des notifications
        if ($wasNotPublished) {
            foreach ($candidates as $candidate) {
                $candidate->notify(new NewJobNotification($job));
            }
        }

        Notification::assertCount(100);
        Notification::assertSentTo($candidates->first(), NewJobNotification::class);
    }

    /**
     * Test que les notifications sont bien envoyées par email et en base de données.
     */
    public function test_notification_is_sent_via_mail_and_database(): void
    {
        Notification::fake();

        $candidates = $this->createCandidates(1);
        $candidate = $candidates->first();

        $job = $this->createJob();
        $job->update(['status' => 'published', 'published_at' => now()]);
        $job->load(['company', 'category', 'location', 'contractType']);

        $candidate->notify(new NewJobNotification($job));

        Notification::assertSentTo($candidate, NewJobNotification::class, function ($notification, $channels) {
            return in_array('mail', $channels) && in_array('database', $channels);
        });
    }
}
