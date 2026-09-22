<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Recruiter;
use App\Models\User;
use Database\Seeders\CompanyCategorySeeder;
use Database\Seeders\EntreprisesCamerounSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Couvre l'import de l'annuaire des 314 entreprises de Douala, Yaoundé et
 * Bafoussam : extraction des coordonnées GPS depuis les liens hypertexte du
 * .xlsx, création des comptes propriétaires et idempotence du seeder.
 */
class EntreprisesCamerounSeederTest extends TestCase
{
    use RefreshDatabase;

    private const EXPECTED_ROWS = 314;

    protected function setUp(): void
    {
        parent::setUp();

        if (! is_file(base_path('entreprises_douala_yaounde_bafoussam.xlsx'))) {
            $this->markTestSkipped('Fichier annuaire absent du dépôt.');
        }
    }

    public function test_il_importe_les_314_entreprises_avec_leurs_coordonnees_gps(): void
    {
        $this->seed(EntreprisesCamerounSeeder::class);

        $companies = Company::whereIn('city', ['Douala', 'Yaoundé', 'Bafoussam'])->get();

        $this->assertCount(self::EXPECTED_ROWS, $companies);

        // Le cœur de l'import : les GPS ne sont pas dans les cellules du
        // fichier mais dans les liens hypertexte. La couverture doit être totale.
        $this->assertSame(
            self::EXPECTED_ROWS,
            $companies->whereNotNull('latitude')->whereNotNull('longitude')->count(),
            'Toutes les entreprises doivent être géolocalisées.'
        );

        // Et rester dans les bornes du Cameroun.
        foreach ($companies as $company) {
            $this->assertGreaterThanOrEqual(1.6, (float) $company->latitude);
            $this->assertLessThanOrEqual(13.1, (float) $company->latitude);
            $this->assertGreaterThanOrEqual(8.4, (float) $company->longitude);
            $this->assertLessThanOrEqual(16.2, (float) $company->longitude);
        }
    }

    public function test_il_repartit_les_entreprises_sur_les_trois_villes(): void
    {
        $this->seed(EntreprisesCamerounSeeder::class);

        $this->assertSame(127, Company::where('city', 'Douala')->count());
        $this->assertSame(99, Company::where('city', 'Yaoundé')->count());
        $this->assertSame(88, Company::where('city', 'Bafoussam')->count());
    }

    public function test_chaque_entreprise_recoit_un_compte_proprietaire(): void
    {
        $this->seed(EntreprisesCamerounSeeder::class);

        $this->assertSame(self::EXPECTED_ROWS, User::where('role', 'recruiter')->count());
        $this->assertSame(self::EXPECTED_ROWS, Recruiter::count());

        $company = Company::where('email', 'contact@theyard.cm')->first();

        $this->assertNotNull($company, 'L\'e-mail doit être dérivé du nom commercial.');
        $this->assertSame('The Yard', $company->name);
        $this->assertSame('Douala', $company->city);
        $this->assertSame('verified', $company->status);
        $this->assertSame('+237 671 49 07 33', $company->phone);
        $this->assertEqualsWithDelta(4.027792, (float) $company->latitude, 0.000001);
        $this->assertEqualsWithDelta(9.701338, (float) $company->longitude, 0.000001);

        $recruiter = $company->recruiters()->with('user')->first();

        $this->assertNotNull($recruiter);
        $this->assertSame('recruiter', $recruiter->user->role);
        $this->assertSame($company->id, $recruiter->user->current_company_id);
        $this->assertTrue((bool) $recruiter->user->must_change_password);
        $this->assertTrue((bool) $recruiter->can_publish);
    }

    public function test_les_adresses_email_sont_uniques(): void
    {
        $this->seed(EntreprisesCamerounSeeder::class);

        $emails = Company::whereIn('city', ['Douala', 'Yaoundé', 'Bafoussam'])
            ->pluck('email');

        $this->assertCount(self::EXPECTED_ROWS, $emails->unique());

        foreach ($emails as $email) {
            $this->assertNotFalse(
                filter_var($email, FILTER_VALIDATE_EMAIL),
                "E-mail invalide généré : {$email}"
            );
        }
    }

    public function test_il_est_idempotent(): void
    {
        $this->seed(EntreprisesCamerounSeeder::class);

        $companies = Company::count();
        $users = User::count();
        $recruiters = Recruiter::count();

        // Une seconde exécution ne doit rien créer ni modifier.
        $this->seed(EntreprisesCamerounSeeder::class);

        $this->assertSame($companies, Company::count());
        $this->assertSame($users, User::count());
        $this->assertSame($recruiters, Recruiter::count());
    }

    public function test_il_preserve_les_entreprises_existantes(): void
    {
        $existing = Company::create([
            'name' => 'Entreprise Historique',
            'email' => 'contact@historique.test',
            'sector' => 'Test',
            'city' => 'Douala',
            'status' => 'pending',
        ]);

        $this->seed(EntreprisesCamerounSeeder::class);

        $existing->refresh();

        $this->assertSame('Entreprise Historique', $existing->name);
        $this->assertSame('pending', $existing->status);
        $this->assertNull($existing->latitude);
    }

    public function test_il_rattache_les_entreprises_aux_categories_de_l_annuaire(): void
    {
        $this->seed(CompanyCategorySeeder::class);
        $this->seed(EntreprisesCamerounSeeder::class);

        $company = Company::where('email', 'contact@theyard.cm')->first();

        $this->assertSame(
            'Hôtellerie, Restauration & Tourisme',
            $company->categories->first()?->level_1
        );
    }

    public function test_les_entreprises_sont_trouvables_par_proximite(): void
    {
        $this->seed(EntreprisesCamerounSeeder::class);

        // Akwa, centre-ville de Douala.
        $nearby = Company::nearby(4.0511, 9.7679, 5)->get();

        $this->assertNotEmpty($nearby, 'La recherche de proximité doit remonter des résultats.');

        foreach ($nearby as $company) {
            $this->assertLessThanOrEqual(5, $company->distanceTo(4.0511, 9.7679));
        }
    }
}
