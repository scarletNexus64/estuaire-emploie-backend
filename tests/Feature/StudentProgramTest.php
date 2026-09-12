<?php

namespace Tests\Feature;

use App\Models\QuickService;
use App\Models\ServiceCategory;
use App\Models\User;
use Database\Seeders\StudentProgramSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Couvre les 4 « jobs étudiants » du groupe Estuaire : leur publication sous
 * le compte plateforme, le calcul de leur rémunération, et leur séparation
 * d'avec les services rapides ordinaires.
 */
class StudentProgramTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Le seeder exige un administrateur : c'est lui qui publie les programmes.
     */
    private function seedPrograms(): void
    {
        User::factory()->create(['role' => 'admin', 'is_super_admin' => true]);
        $this->seed(StudentProgramSeeder::class);
    }

    public function test_the_seeder_creates_the_four_programs(): void
    {
        $this->seedPrograms();

        $this->assertSame(4, QuickService::where('is_student_program', true)->count());

        $this->assertEqualsCanonicalizing(
            ['estuaire_eat', 'estuaire_achats', 'estuaire_emploi', 'merci_e'],
            QuickService::studentPrograms()->pluck('program_partner')->all()
        );
    }

    public function test_the_seeder_can_be_replayed_without_creating_duplicates(): void
    {
        $this->seedPrograms();
        $this->seed(StudentProgramSeeder::class);

        $this->assertSame(4, QuickService::where('is_student_program', true)->count());
    }

    public function test_programs_are_published_under_the_platform_admin_account(): void
    {
        $this->seedPrograms();

        foreach (QuickService::studentPrograms()->with('user')->get() as $program) {
            $this->assertSame('admin', $program->user->role);
        }
    }

    public function test_programs_are_open_nationwide_and_never_expire(): void
    {
        $this->seedPrograms();

        foreach (QuickService::studentPrograms()->get() as $program) {
            $this->assertSame('open', $program->status);
            $this->assertNotNull($program->approved_at);
            $this->assertNull($program->expires_at);
            // Programme national : aucune géolocalisation.
            $this->assertNull($program->latitude);
            $this->assertNull($program->longitude);
        }
    }

    public function test_programs_use_their_own_service_category(): void
    {
        $this->seedPrograms();

        $category = ServiceCategory::where('slug', 'jobs-etudiants')->firstOrFail();

        $this->assertSame(
            4,
            QuickService::studentPrograms()->where('service_category_id', $category->id)->count()
        );
    }

    /**
     * Les taux et montants doivent correspondre au cahier des charges.
     */
    public function test_each_program_carries_the_expected_compensation_terms(): void
    {
        $this->seedPrograms();

        $programs = QuickService::studentPrograms()->get()->keyBy('program_partner');

        $eat = $programs['estuaire_eat'];
        $this->assertEquals(5, $eat->commission_rate);
        $this->assertSame('first_month_revenue', $eat->commission_basis);
        $this->assertSame(30, $eat->min_active_days);

        $achats = $programs['estuaire_achats'];
        $this->assertEquals(3, $achats->commission_rate);
        $this->assertSame('first_transaction', $achats->commission_basis);
        // Plafond anti-abus exigé par le cahier des charges.
        $this->assertNotNull($achats->commission_cap);

        $emploi = $programs['estuaire_emploi'];
        $this->assertEquals(5, $emploi->commission_rate);
        $this->assertSame('per_transaction', $emploi->commission_basis);
        $this->assertEquals(1000, $emploi->fixed_bonus);
        $this->assertTrue($emploi->bonus_is_cumulative);

        $merci = $programs['merci_e'];
        $this->assertEquals(50, $merci->commission_rate);
        $this->assertSame('per_course', $merci->commission_basis);
        // Merci-E priorise les coursiers les mieux notés.
        $this->assertTrue($merci->has_rating_system);
    }

    public function test_compensation_is_rendered_as_a_readable_sentence(): void
    {
        $this->seedPrograms();

        $programs = QuickService::studentPrograms()->get()->keyBy('program_partner');

        $this->assertSame(
            "5 % du premier mois de chiffre d'affaires généré",
            $programs['estuaire_eat']->formatted_compensation
        );

        $this->assertStringContainsString(
            'plafonné',
            $programs['estuaire_achats']->formatted_compensation
        );

        // Commission ET prime pour Estuaire Emploi : les deux se cumulent.
        $emploi = $programs['estuaire_emploi']->formatted_compensation;
        $this->assertStringContainsString('5 % à chaque transaction', $emploi);
        $this->assertStringContainsString('1 000 FCFA', $emploi);
        $this->assertStringContainsString(' et ', $emploi);

        $this->assertSame(
            '50 % du montant de la course',
            $programs['merci_e']->formatted_compensation
        );
    }

    public function test_the_endpoint_returns_the_four_programs_in_order(): void
    {
        $this->seedPrograms();

        $response = $this->getJson('/api/quick-services/student-programs')
            ->assertOk()
            ->assertJsonPath('success', true);

        $data = $response->json('data');

        $this->assertCount(4, $data);
        $this->assertSame([1, 2, 3, 4], array_column($data, 'program_order'));
        $this->assertSame('estuaire_eat', $data[0]['program_partner']);
        $this->assertSame('merci_e', $data[3]['program_partner']);

        foreach ($data as $program) {
            $this->assertNotEmpty($program['formatted_compensation']);
            $this->assertTrue($program['is_student_program']);
        }
    }

    public function test_the_endpoint_localises_titles_and_compensation(): void
    {
        $this->seedPrograms();

        $this->getJson('/api/quick-services/student-programs?lang=en')
            ->assertOk()
            ->assertJsonPath('data.3.title', 'Courier — Merci-E')
            ->assertJsonPath('data.3.formatted_compensation', '50% of the delivery amount');
    }

    /**
     * Les programmes ne doivent pas polluer la liste des missions ponctuelles.
     */
    public function test_programs_are_excluded_from_the_ordinary_service_list(): void
    {
        $this->seedPrograms();

        $author = User::factory()->create(['role' => 'recruiter']);
        $category = ServiceCategory::where('slug', 'jobs-etudiants')->firstOrFail();

        QuickService::create([
            'user_id' => $author->id,
            'service_category_id' => $category->id,
            'title' => 'Mission ponctuelle de test',
            'description' => 'Une demande classique publiée par un recruteur.',
            'price_type' => 'fixed',
            'price_min' => 5000,
            'latitude' => 4.0511,
            'longitude' => 9.7679,
            'urgency' => 'flexible',
            'status' => 'open',
            'approved_at' => now(),
        ]);

        $listed = $this->getJson('/api/quick-services')->assertOk()->json('data.data');

        $this->assertCount(1, $listed);
        $this->assertSame('Mission ponctuelle de test', $listed[0]['title']);
    }

    public function test_the_ordinary_list_endpoint_still_works_without_any_program(): void
    {
        $this->getJson('/api/quick-services')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('featured_programs', []);
    }

    /**
     * Les annonces sont regroupées par famille côté application : chaque
     * programme doit donc porter son type et ses libellés traduits.
     */
    public function test_each_program_exposes_its_category(): void
    {
        $this->seedPrograms();

        $data = $this->getJson('/api/quick-services/student-programs')
            ->assertOk()
            ->json('data');

        $types = array_column($data, 'program_type');

        $this->assertSame(
            ['business_provider', 'business_provider', 'business_provider', 'courier'],
            $types
        );

        foreach ($data as $program) {
            $this->assertNotEmpty($program['program_type_label']);
            $this->assertNotEmpty($program['program_type_description']);
        }
    }

    public function test_category_labels_are_localised(): void
    {
        $this->seedPrograms();

        $this->getJson('/api/quick-services/student-programs?lang=en')
            ->assertOk()
            ->assertJsonPath('data.0.program_type_label', 'Business referrer')
            ->assertJsonPath('data.3.program_type_label', 'Courier');
    }

    /**
     * Les programmes sont épinglés en tête de la liste des services rapides,
     * à la manière d'annonces sponsorisées.
     */
    public function test_programs_are_pinned_to_the_service_list_as_adverts(): void
    {
        $this->seedPrograms();

        $response = $this->getJson('/api/quick-services')->assertOk();

        $featured = $response->json('featured_programs');

        $this->assertCount(4, $featured);
        $this->assertSame('estuaire_eat', $featured[0]['program_partner']);
        $this->assertNotEmpty($featured[0]['formatted_compensation']);
    }

    /**
     * Ils restent visibles même quand aucune mission ponctuelle n'est ouverte,
     * ce qui était le cas qui donnait une liste entièrement vide.
     */
    public function test_programs_remain_visible_when_no_ordinary_service_exists(): void
    {
        $this->seedPrograms();

        $response = $this->getJson('/api/quick-services')->assertOk();

        $this->assertSame(0, $response->json('data.total'));
        $this->assertCount(4, $response->json('featured_programs'));
    }

    /**
     * Hors pagination : inutile de les répéter à chaque page.
     */
    public function test_pinned_programs_are_served_on_the_first_page_only(): void
    {
        $this->seedPrograms();

        $this->getJson('/api/quick-services?page=2')
            ->assertOk()
            ->assertJsonPath('featured_programs', []);
    }
}
