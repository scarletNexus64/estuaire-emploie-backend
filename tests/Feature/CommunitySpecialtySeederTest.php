<?php

namespace Tests\Feature;

use App\Models\CommunityFiliere;
use App\Models\CommunitySpecialty;
use App\Models\Specialty;
use Database\Seeders\CommunitySpecialtySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CommunitySpecialtySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_is_complete_grouped_and_keeps_source_ids(): void
    {
        Http::preventStrayRequests();
        $this->seed(CommunitySpecialtySeeder::class);
        $this->assertDatabaseCount('community_filieres', 8);
        $this->assertDatabaseCount('community_specialties', 47);
        $this->assertSame([
            'Agriculture et Elevage' => 3,
            'Genie Informatique' => 9,
            'Genie Electrique' => 4,
            'Genie Civil et Genie Thermique' => 6,
            'Genie Mecanique et Productique' => 4,
            'Commerce, Vente et Gestion' => 12,
            'Art, Tourisme et Hotellerie' => 3,
            'Sante' => 6,
        ], CommunityFiliere::orderBy('display_order')->withCount('specialties')->pluck('specialties_count', 'name')->all());

        $catalog = json_decode(file_get_contents(database_path('seeders/data/insam_ia_community_specialties.json')), true);
        foreach ($catalog['categories'] as $category) {
            $specialty = CommunitySpecialty::where('insam_ia_category_id', $category['id'])->firstOrFail();
            $this->assertSame($category['name'], $specialty->name);
            $this->assertSame($category['filiere_name'], $specialty->filiere->name);
            $this->assertTrue($specialty->is_active);
        }
    }

    public function test_import_is_repeatable_and_preserves_existing_data(): void
    {
        $existing = Specialty::create(['name' => 'Informatique', 'slug' => 'informatique', 'is_active' => true]);
        $this->seed(CommunitySpecialtySeeder::class);
        $ids = CommunitySpecialty::orderBy('id')->pluck('id', 'insam_ia_category_id')->all();
        $specialty = CommunitySpecialty::firstOrFail();
        $specialty->update(['is_active' => false, 'description' => 'Description adaptée par un administrateur.']);
        $this->seed(CommunitySpecialtySeeder::class);

        $this->assertDatabaseCount('community_filieres', 8);
        $this->assertDatabaseCount('community_specialties', 47);
        $this->assertSame($ids, CommunitySpecialty::orderBy('id')->pluck('id', 'insam_ia_category_id')->all());
        $this->assertFalse($specialty->fresh()->is_active);
        $this->assertSame('Description adaptée par un administrateur.', $specialty->fresh()->description);
        $this->assertDatabaseCount('specialties', 1);
        $this->assertSame('Informatique', $existing->fresh()->name);
    }
}
