<?php

namespace Tests\Feature;

use App\Models\Advertisement;
use App\Models\User;
use Database\Seeders\DefaultAdvertisementSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Couvre le carrousel de l'accueil : bascule campagnes ↔ bannières par défaut,
 * ciblage par rôle, destination du clic et localisation des textes.
 */
class AdvertisementBannerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Crée une campagne payante diffusable (donc prioritaire sur le repli).
     */
    private function activeCampaign(array $attributes = []): Advertisement
    {
        return Advertisement::create(array_merge([
            'title' => 'Campagne sponsorisée',
            'description' => 'Campagne payante en cours',
            'background_color' => '#123456',
            'ad_type' => 'homepage_banner',
            'target_audience' => 'all',
            'source' => 'self_service',
            'content_type' => 'text',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDays(5),
            'is_active' => true,
            'is_default' => false,
            'status' => 'active',
            'display_order' => 0,
        ], $attributes));
    }

    public function test_the_seeder_creates_the_thirteen_default_banners(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);

        $this->assertSame(13, Advertisement::where('is_default', true)->count());
    }

    public function test_the_seeder_can_be_replayed_without_creating_duplicates(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);
        $this->seed(DefaultAdvertisementSeeder::class);

        $this->assertSame(13, Advertisement::where('is_default', true)->count());
    }

    public function test_default_banners_are_served_when_no_campaign_is_running(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);

        $response = $this->getJson('/api/advertisements')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('is_fallback', true);

        // Un visiteur anonyme ne voit pas la bannière réservée aux recruteurs.
        $this->assertCount(12, $response->json('data'));
    }

    public function test_a_running_campaign_replaces_the_default_banners(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);
        $this->activeCampaign();

        $response = $this->getJson('/api/advertisements')
            ->assertOk()
            ->assertJsonPath('is_fallback', false);

        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Campagne sponsorisée', $response->json('data.0.title'));
    }

    public function test_a_campaign_outside_its_window_falls_back_to_the_defaults(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);
        $this->activeCampaign([
            'start_date' => now()->subMonth(),
            'end_date' => now()->subDay(),
        ]);

        $this->getJson('/api/advertisements')
            ->assertOk()
            ->assertJsonPath('is_fallback', true);
    }

    public function test_a_campaign_targeting_recruiters_does_not_hide_defaults_from_a_candidate(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);
        $this->activeCampaign(['target_audience' => 'recruiter']);

        $candidate = User::factory()->create(['role' => 'candidate']);

        $this->actingAs($candidate, 'sanctum')
            ->getJson('/api/advertisements')
            ->assertOk()
            ->assertJsonPath('is_fallback', true);
    }

    public function test_a_recruiter_also_sees_the_recruiter_only_default_banner(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);

        $recruiter = User::factory()->create(['role' => 'recruiter']);

        $response = $this->actingAs($recruiter, 'sanctum')
            ->getJson('/api/advertisements')
            ->assertOk()
            ->assertJsonPath('is_fallback', true);

        $this->assertCount(13, $response->json('data'));
        $this->assertContains('default-recruiters', collect($response->json('data'))->pluck('slug')->all());
    }

    public function test_each_default_banner_exposes_a_redirect_destination(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);

        $banners = $this->getJson('/api/advertisements')->assertOk()->json('data');

        foreach ($banners as $banner) {
            $this->assertNotSame('none', $banner['redirect']['type'], "Bannière sans destination : {$banner['slug']}");
            $this->assertNotNull($banner['redirect']['target'], "Bannière sans cible : {$banner['slug']}");
        }
    }

    public function test_the_whatsapp_banners_expose_a_ready_to_open_wa_me_link(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);

        $networking = Advertisement::where('slug', 'default-networking')->firstOrFail();

        $this->assertSame('whatsapp', $networking->redirect['type']);
        $this->assertStringStartsWith('https://wa.me/237696118389?text=', $networking->redirect['target']);
    }

    public function test_recording_a_click_returns_the_destination_and_increments_the_counter(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);

        $banner = Advertisement::where('slug', 'default-dream-job')->firstOrFail();

        $this->postJson("/api/advertisements/{$banner->id}/click")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('redirect.type', 'internal_route')
            ->assertJsonPath('redirect.target', '/search');

        $this->assertSame(1, $banner->fresh()->clicks_count);
    }

    public function test_banner_texts_are_returned_in_the_requested_locale(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);

        $this->getJson('/api/advertisements?lang=en')
            ->assertOk()
            ->assertJsonPath('data.0.title', 'Grow your career');

        $this->getJson('/api/advertisements?lang=fr')
            ->assertOk()
            ->assertJsonPath('data.0.title', 'Développez votre carrière');
    }

    public function test_deactivated_default_banners_are_not_served(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);

        Advertisement::where('slug', 'default-dream-job')->update(['is_active' => false]);

        $slugs = collect($this->getJson('/api/advertisements')->json('data'))->pluck('slug');

        $this->assertNotContains('default-dream-job', $slugs);
    }

    public function test_the_admin_form_exposes_the_redirect_fields(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);

        $admin = User::factory()->create(['role' => 'admin', 'is_super_admin' => true]);
        $banner = Advertisement::where('slug', 'default-networking')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.advertisements.edit', $banner->id))
            ->assertOk()
            ->assertSee('redirect_type')
            ->assertSee('redirect_target')
            ->assertSee('237696118389');
    }

    public function test_an_admin_can_set_a_redirect_on_a_banner(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);

        $admin = User::factory()->create(['role' => 'admin', 'is_super_admin' => true]);
        $banner = Advertisement::where('slug', 'default-startups')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.advertisements.update', $banner->id), [
                'title' => $banner->title,
                'description' => $banner->description,
                'background_color' => $banner->background_color,
                'ad_type' => 'homepage_banner',
                'redirect_type' => 'external_url',
                'redirect_target' => 'https://estuaireemploi.com/startups',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'display_order' => $banner->display_order,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.advertisements.index'));

        $banner->refresh();
        $this->assertSame('external_url', $banner->redirect_type);
        $this->assertSame('https://estuaireemploi.com/startups', $banner->redirect_target);
    }

    public function test_clearing_the_redirect_type_also_clears_the_destination(): void
    {
        $this->seed(DefaultAdvertisementSeeder::class);

        $admin = User::factory()->create(['role' => 'admin', 'is_super_admin' => true]);
        $banner = Advertisement::where('slug', 'default-startups')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.advertisements.update', $banner->id), [
                'title' => $banner->title,
                'description' => $banner->description,
                'background_color' => $banner->background_color,
                'ad_type' => 'homepage_banner',
                'redirect_type' => 'none',
                'redirect_target' => '/companies-directory',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'display_order' => $banner->display_order,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.advertisements.index'));

        $banner->refresh();
        $this->assertSame('none', $banner->redirect_type);
        $this->assertNull($banner->redirect_target);
        $this->assertSame('none', $banner->redirect['type']);
    }
}
