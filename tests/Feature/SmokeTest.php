<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Vérifie que l'application démarre réellement : routage, base de données,
 * configuration et factories. Ces tests remplacent les stubs Laravel Breeze
 * livrés par défaut, qui visaient des routes web (/login, /profile) que ce
 * projet n'expose pas — l'authentification passe par /admin/login et l'API.
 */
class SmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_check_endpoint_responds(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_root_redirects_guests_to_the_admin_login(): void
    {
        $this->get('/')
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_protected_admin_route_redirects_guests(): void
    {
        $this->get('/admin/dashboard')
            ->assertRedirect('/admin/login');
    }

    public function test_user_factory_persists_a_user(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
            'role' => 'candidate',
        ]);
    }

    public function test_api_rejects_unauthenticated_requests(): void
    {
        $this->getJson('/api/user')->assertUnauthorized();
    }
}
