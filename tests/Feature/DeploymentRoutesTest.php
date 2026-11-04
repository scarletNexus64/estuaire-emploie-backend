<?php

namespace Tests\Feature;

use App\Http\Middleware\CheckPortfolioAccess;
use App\Models\Company;
use App\Models\Portfolio;
use App\Models\Recruiter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeploymentRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_portfolio_update_saves_only_the_authenticated_users_validated_fields(): void
    {
        $this->withoutMiddleware(CheckPortfolioAccess::class);
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $portfolio = Portfolio::create(['user_id' => $owner->id, 'title' => 'Ancien titre']);
        $otherPortfolio = Portfolio::create(['user_id' => $other->id, 'title' => 'Autre portfolio']);

        $this->actingAs($owner, 'sanctum')->putJson('/api/portfolio', [
            'title' => 'Nouveau titre', 'bio' => 'Présentation mise à jour',
            'user_id' => $other->id, 'view_count' => 999,
        ])->assertOk()->assertJsonPath('data.id', $portfolio->id)
            ->assertJsonPath('data.user_id', $owner->id)->assertJsonPath('data.title', 'Nouveau titre');

        $this->assertSame('Autre portfolio', $otherPortfolio->fresh()->title);
        $this->assertSame(0, $portfolio->fresh()->view_count);
        $this->assertDatabaseCount('portfolios', 2);
        $this->putJson('/api/portfolio', ['title' => ''])->assertUnprocessable();
        $this->assertSame('Nouveau titre', $portfolio->fresh()->title);
    }

    public function test_recruiter_detail_opens_the_existing_management_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_super_admin' => true]);
        $user = User::factory()->create(['role' => 'recruiter']);
        $company = Company::create(['name' => 'Entreprise test', 'email' => 'test@example.test', 'status' => 'verified']);
        $recruiter = Recruiter::create(['user_id' => $user->id, 'company_id' => $company->id]);

        $this->get('/admin/recruiters/'.$recruiter->id)->assertRedirect('/admin/login');
        $this->actingAs($admin)->get('/admin/recruiters/'.$recruiter->id)
            ->assertRedirect(route('admin.recruiters.edit', $recruiter));
        $this->get(route('admin.recruiters.edit', $recruiter))->assertOk();
        $this->get('/admin/recruiters/999999')->assertNotFound();
    }

    public function test_withdrawals_remain_readable_when_the_owner_has_deleted_their_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_super_admin' => true]);
        $owner = User::factory()->create(['paypal_wallet_balance' => 5000]);
        $withdrawal = \App\Models\WithdrawalRequest::create([
            'user_id' => $owner->id, 'amount' => 2000,
            'paypal_email' => 'deleted@example.test', 'status' => 'pending',
        ]);
        $owner->delete();

        $this->actingAs($admin)->get('/admin/withdrawal-requests')->assertOk()->assertSee('Compte supprimé');
        $url = route('admin.withdrawal-requests.show', $withdrawal);
        $this->get($url)->assertOk()->assertSee('Compte supprimé')->assertDontSee('id="responseForm"', false);
        $this->from($url)->post(route('admin.withdrawal-requests.respond', $withdrawal), ['action' => 'approve'])
            ->assertRedirect($url)->assertSessionHas('error');
        $this->assertSame('pending', $withdrawal->fresh()->status);
        $this->assertEquals(5000, User::withTrashed()->findOrFail($owner->id)->paypal_wallet_balance);
    }

    public function test_formation_pricing_uses_the_local_catalog_without_waiting_for_the_remote_api(): void
    {
        \Illuminate\Support\Facades\Http::preventStrayRequests();
        $this->mock(\App\Services\InsamtechsCatalogService::class)
            ->shouldReceive('catalog')->once()->andReturn([
                ['id' => 123, 'intitule' => ['fr' => 'Formation locale'], 'etat' => 1],
                ['id' => 456, 'intitule' => 'Formation inactive', 'etat' => 0],
            ]);
        $admin = User::factory()->create(['role' => 'admin', 'is_super_admin' => true]);
        $this->actingAs($admin)->get('/admin/insamtechs-pricing/create')
            ->assertOk()->assertSee('Formation locale')->assertDontSee('Formation inactive');
    }
}
