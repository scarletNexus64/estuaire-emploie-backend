<?php

namespace Tests\Feature;

use App\Models\InsamIa\InsamIaAttempt;
use App\Models\InsamIa\InsamIaAttestation;
use App\Models\InsamIa\InsamIaReadingProgress;
use App\Models\PremiumServiceConfig;
use App\Models\User;
use App\Models\UserPremiumService;
use App\Services\InsamIa\InsamIaAttestationException;
use App\Services\InsamIa\InsamIaAttestationService;
use App\Services\InsamIa\InsamIaClient;
use App\Services\InsamIa\InsamIaException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Couvre l'intégration INSAM-IA : accès aux ressources, gestion des pannes du
 * service tiers, et délivrance des attestations par Estuaire.
 */
class InsamIaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.insam_ia.base_url' => 'https://insam-ia.test',
            'services.insam_ia.email' => 'compte@insam-ia.test',
            'services.insam_ia.password' => 'secret',
            'services.insam_ia.api_key' => 'cle-de-test',
        ]);

        Cache::flush();
    }

    /**
     * Un étudiant disposant du Mode Étudiant (le gating des ressources).
     */
    private function student(): User
    {
        $user = User::factory()->create(['role' => 'candidate']);

        $config = PremiumServiceConfig::create([
            'name' => 'Mode Étudiant',
            'slug' => 'student_mode',
            'description' => 'Accès aux ressources étudiantes',
            'price' => 0,
            'duration_days' => 30,
            'service_type' => 'student_mode',
            'is_active' => true,
        ]);

        UserPremiumService::create([
            'user_id' => $user->id,
            'premium_services_config_id' => $config->id,
            'purchased_at' => now(),
            'activated_at' => now(),
            'expires_at' => now()->addDays(30),
            'is_active' => true,
        ]);

        return $user->fresh();
    }

    /**
     * Simule INSAM-IA : authentification puis réponses applicatives.
     */
    private function fakeInsamIa(array $routes = []): void
    {
        Http::fake(array_merge([
            'insam-ia.test/api/login' => Http::response(['token' => '42|jeton-de-test'], 200),
        ], $routes));
    }

    // ------------------------------------------------------------------
    // Accès
    // ------------------------------------------------------------------

    public function test_resources_require_student_access(): void
    {
        $user = User::factory()->create(['role' => 'candidate']);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/insam-ia/exams')
            ->assertStatus(403)
            ->assertJsonPath('requires_student_mode', true);
    }

    public function test_status_reports_configuration_and_access(): void
    {
        $this->actingAs($this->student(), 'sanctum')
            ->getJson('/api/insam-ia/status')
            ->assertOk()
            ->assertJsonPath('data.configured', true)
            ->assertJsonPath('data.has_access', true)
            ->assertJsonPath('data.pass_threshold', InsamIaAttestation::PASS_THRESHOLD);
    }

    // ------------------------------------------------------------------
    // Ressource 1 — Packs d'épreuves
    // ------------------------------------------------------------------

    public function test_exams_are_listed_and_searchable(): void
    {
        $this->fakeInsamIa([
            'insam-ia.test/api/exams*' => Http::response([
                'exams' => [
                    ['id' => 1, 'title' => 'MATHÉMATIQUES B1', 'is_corrected' => true, 'category' => ['id' => 9, 'name' => 'BÂTIMENT']],
                    ['id' => 2, 'title' => "TECHNIQUES D'HÉBERGEMENT", 'is_corrected' => false, 'category' => ['id' => 25, 'name' => 'HÔTELLERIE']],
                ],
            ], 200),
        ]);

        $student = $this->student();

        $this->actingAs($student, 'sanctum')
            ->getJson('/api/insam-ia/exams')
            ->assertOk()
            ->assertJsonPath('meta.total', 2);

        // INSAM-IA ignore le paramètre `search` : le filtrage est fait par
        // Estuaire, sans tenir compte des accents.
        $response = $this->actingAs($student, 'sanctum')
            ->getJson('/api/insam-ia/exams?search=hebergement')
            ->assertOk();

        $this->assertSame(1, $response->json('meta.total'));
        $this->assertSame(2, $response->json('data.0.id'));
    }

    public function test_exam_download_is_proxied_through_estuaire(): void
    {
        $this->fakeInsamIa([
            'insam-ia.test/api/exams/1/download' => Http::response('CONTENU-BINAIRE', 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="sujet.pdf"',
            ]),
        ]);

        $response = $this->actingAs($this->student(), 'sanctum')
            ->get('/api/insam-ia/exams/1/download');

        $response->assertOk();
        $this->assertSame('CONTENU-BINAIRE', $response->streamedContent());
    }

    // ------------------------------------------------------------------
    // Ressource 5 — Fiches de révision
    // ------------------------------------------------------------------

    public function test_revision_cards_expose_their_key_points(): void
    {
        $this->fakeInsamIa([
            'insam-ia.test/api/revision-cards' => Http::response([
                'data' => [[
                    'id' => 11,
                    'title' => 'Fiche — BÂTIMENT',
                    'summary' => 'Résumé',
                    // INSAM-IA renvoie parfois une chaîne JSON plutôt qu'un tableau.
                    'key_points' => '["Point A","Point B"]',
                    'category' => ['id' => 9, 'name' => 'BÂTIMENT'],
                ]],
            ], 200),
        ]);

        $this->actingAs($this->student(), 'sanctum')
            ->getJson('/api/insam-ia/revision-cards')
            ->assertOk()
            ->assertJsonPath('data.0.id', 11)
            ->assertJsonPath('data.0.key_points', ['Point A', 'Point B']);
    }

    public function test_revision_card_generation_is_queued(): void
    {
        \Illuminate\Support\Facades\Queue::fake();

        $this->actingAs($this->student(), 'sanctum')
            ->postJson('/api/insam-ia/revision-cards/generate', ['category_id' => 9])
            ->assertStatus(202)
            ->assertJsonPath('success', true);

        \Illuminate\Support\Facades\Queue::assertPushed(\App\Jobs\GenerateInsamIaRevisionCard::class);
    }

    // ------------------------------------------------------------------
    // Pannes du service tiers
    // ------------------------------------------------------------------

    public function test_an_upstream_outage_is_reported_as_service_unavailable(): void
    {
        $this->fakeInsamIa([
            'insam-ia.test/api/exams*' => Http::response('Bad gateway', 502),
        ]);

        $this->actingAs($this->student(), 'sanctum')
            ->getJson('/api/insam-ia/exams')
            ->assertStatus(503)
            ->assertJsonPath('success', false)
            ->assertJsonPath('service_unavailable', true);
    }

    public function test_missing_credentials_disable_the_integration_cleanly(): void
    {
        config([
            'services.insam_ia.email' => null,
            'services.insam_ia.password' => null,
        ]);

        $student = $this->student();

        $this->actingAs($student, 'sanctum')
            ->getJson('/api/insam-ia/status')
            ->assertOk()
            ->assertJsonPath('data.configured', false);

        $this->actingAs($student, 'sanctum')
            ->getJson('/api/insam-ia/exams')
            ->assertStatus(503)
            ->assertJsonPath('service_unavailable', true);
    }

    public function test_a_missing_resource_is_a_404_not_an_outage(): void
    {
        $this->fakeInsamIa([
            'insam-ia.test/api/exams/999' => Http::response(['message' => 'Not found'], 404),
        ]);

        $this->actingAs($this->student(), 'sanctum')
            ->getJson('/api/insam-ia/exams/999')
            ->assertStatus(404)
            ->assertJsonPath('service_unavailable', false);
    }

    public function test_an_expired_token_is_renewed_transparently(): void
    {
        // Jeton périmé en cache : le premier appel doit échouer en 401, puis
        // le client se ré-authentifie et rejoue la requête.
        Cache::put('insam_ia:token', 'jeton-perime', 600);

        Http::fake([
            'insam-ia.test/api/login' => Http::response(['token' => 'jeton-neuf'], 200),
            'insam-ia.test/api/exams*' => Http::sequence()
                ->push(['message' => 'Unauthenticated.'], 401)
                ->push(['exams' => [['id' => 5, 'title' => 'ÉPREUVE', 'category' => null]]], 200),
        ]);

        $this->actingAs($this->student(), 'sanctum')
            ->getJson('/api/insam-ia/exams')
            ->assertOk()
            ->assertJsonPath('meta.total', 1);

        $this->assertSame('jeton-neuf', Cache::get('insam_ia:token'));
    }

    public function test_the_client_reports_an_unreachable_service(): void
    {
        Http::fake(fn () => throw new \Illuminate\Http\Client\ConnectionException('Connexion impossible'));

        $this->expectException(InsamIaException::class);

        app(InsamIaClient::class)->get('/api/exams');
    }

    // ------------------------------------------------------------------
    // Ressource 4 — Progression de lecture
    // ------------------------------------------------------------------

    public function test_reading_progress_never_goes_backwards(): void
    {
        $student = $this->student();

        $this->actingAs($student, 'sanctum')
            ->postJson('/api/insam-ia/reading-progress', [
                'resource_type' => 'revision_card',
                'resource_id' => 11,
                'progress_percent' => 60,
            ])
            ->assertOk()
            ->assertJsonPath('data.progress_percent', 60);

        // Rouvrir la fiche au début ne doit pas effacer l'avancement.
        $this->actingAs($student, 'sanctum')
            ->postJson('/api/insam-ia/reading-progress', [
                'resource_type' => 'revision_card',
                'resource_id' => 11,
                'progress_percent' => 5,
            ])
            ->assertOk()
            ->assertJsonPath('data.progress_percent', 60);

        $this->assertSame(1, InsamIaReadingProgress::where('user_id', $student->id)->count());
    }

    public function test_completing_a_resource_marks_it_as_finished(): void
    {
        $student = $this->student();

        $this->actingAs($student, 'sanctum')
            ->postJson('/api/insam-ia/reading-progress', [
                'resource_type' => 'revision_card',
                'resource_id' => 11,
                'progress_percent' => 100,
            ])
            ->assertOk()
            ->assertJsonPath('data.completed', true);

        $this->assertNotNull(
            InsamIaReadingProgress::where('user_id', $student->id)->first()->completed_at
        );
    }

    // ------------------------------------------------------------------
    // Ressource 4 — Attestations
    // ------------------------------------------------------------------

    private function submittedAttempt(User $user, int $percentage): InsamIaAttempt
    {
        return InsamIaAttempt::create([
            'user_id' => $user->id,
            'session_id' => 773,
            'remote_attempt_id' => 999,
            'session_title' => "Économie générale\nDescription",
            'specialite' => 'IM1',
            'status' => 'submitted',
            'score' => (int) round($percentage / 5),
            'total' => 20,
            'percentage' => $percentage,
            'submitted_at' => now(),
        ]);
    }

    public function test_a_passing_score_yields_an_attestation_with_a_pdf(): void
    {
        $student = $this->student();
        $attempt = $this->submittedAttempt($student, 85);

        $response = $this->actingAs($student, 'sanctum')
            ->postJson("/api/insam-ia/evaluations/{$attempt->id}/attestation")
            ->assertStatus(201)
            ->assertJsonPath('data.percentage', 85)
            ->assertJsonPath('data.mention', 'tres_bien');

        $attestation = InsamIaAttestation::find($response->json('data.id'));

        $this->assertNotNull($attestation->pdf_path);
        $this->assertStringStartsWith('EE-ATT-', $attestation->reference);
        // Les retours à la ligne des intitulés INSAM-IA sont normalisés.
        $this->assertStringNotContainsString("\n", $attestation->title);
    }

    public function test_a_failing_score_is_refused_with_the_threshold(): void
    {
        $student = $this->student();
        $attempt = $this->submittedAttempt($student, 55);

        $this->actingAs($student, 'sanctum')
            ->postJson("/api/insam-ia/evaluations/{$attempt->id}/attestation")
            ->assertStatus(422)
            ->assertJsonPath('pass_threshold', InsamIaAttestation::PASS_THRESHOLD);

        $this->assertSame(0, InsamIaAttestation::count());
    }

    public function test_issuing_twice_returns_the_same_attestation(): void
    {
        $student = $this->student();
        $attempt = $this->submittedAttempt($student, 92);

        $service = app(InsamIaAttestationService::class);

        $first = $service->issueFor($attempt);
        $second = $service->issueFor($attempt);

        $this->assertSame($first->id, $second->id);
        $this->assertSame($first->reference, $second->reference);
        $this->assertSame(1, InsamIaAttestation::count());
    }

    public function test_an_unsubmitted_attempt_cannot_be_rewarded(): void
    {
        $student = $this->student();

        $attempt = InsamIaAttempt::create([
            'user_id' => $student->id,
            'session_id' => 773,
            'status' => 'started',
            'started_at' => now(),
        ]);

        $this->expectException(InsamIaAttestationException::class);

        app(InsamIaAttestationService::class)->issueFor($attempt);
    }

    public function test_mentions_follow_the_score(): void
    {
        $this->assertSame('excellent', InsamIaAttestation::mentionFor(95));
        $this->assertSame('tres_bien', InsamIaAttestation::mentionFor(85));
        $this->assertSame('bien', InsamIaAttestation::mentionFor(76));
        $this->assertSame('assez_bien', InsamIaAttestation::mentionFor(70));

        $this->assertTrue(InsamIaAttestation::isEligible(70));
        $this->assertFalse(InsamIaAttestation::isEligible(69));
        $this->assertFalse(InsamIaAttestation::isEligible(null));
    }

    public function test_attestations_stay_available_while_insam_ia_is_down(): void
    {
        $student = $this->student();
        $attempt = $this->submittedAttempt($student, 88);

        app(InsamIaAttestationService::class)->issueFor($attempt);

        // Service tiers injoignable : les données d'Estuaire restent servies.
        Http::fake(fn () => throw new \Illuminate\Http\Client\ConnectionException('down'));

        $this->actingAs($student, 'sanctum')
            ->getJson('/api/insam-ia/attestations')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->actingAs($student, 'sanctum')
            ->getJson('/api/insam-ia/progress')
            ->assertOk()
            ->assertJsonPath('data.summary.evaluation.passed', true);
    }

    public function test_the_attestation_pdf_can_be_downloaded(): void
    {
        $student = $this->student();
        $attestation = app(InsamIaAttestationService::class)
            ->issueFor($this->submittedAttempt($student, 80));

        $response = $this->actingAs($student, 'sanctum')
            ->get("/api/insam-ia/attestations/{$attestation->id}/download");

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->streamedContent());
    }

    public function test_an_attestation_belonging_to_someone_else_is_not_reachable(): void
    {
        $owner = $this->student();
        $attestation = app(InsamIaAttestationService::class)
            ->issueFor($this->submittedAttempt($owner, 90));

        $intruder = User::factory()->create(['role' => 'candidate']);

        $this->actingAs($intruder, 'sanctum')
            ->get("/api/insam-ia/attestations/{$attestation->id}/download")
            ->assertStatus(404);
    }
}
