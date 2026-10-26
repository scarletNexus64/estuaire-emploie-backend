<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyRecoveryRequest;
use App\Models\Recruiter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyRecoveryPresentationTest extends TestCase
{
    use RefreshDatabase;

    private function company(): Company
    {
        $company = Company::create(['name' => 'Atelier de quartier', 'email' => 'import@example.test',
            'description' => str_repeat('Description de notre activité. ', 30), 'phone' => '+237690000000',
            'city' => 'Douala', 'country' => 'Cameroun', 'sector' => 'Artisanat', 'status' => 'verified',
            'address' => 'Akwa', 'website' => 'https://example.test', 'photos' => ['https://example.test/photo.jpg']]);
        $owner = User::factory()->create(['role' => 'recruiter', 'email_verified_at' => null,
            'must_change_password' => true, 'current_company_id' => $company->id]);
        Recruiter::create(['user_id' => $owner->id, 'company_id' => $company->id]);
        $company->forceFill(['recovery_source' => 'test_import', 'recovery_user_id' => $owner->id])->save();
        return $company;
    }

    private function claim(Company $company, User $user, string $status = 'pending'): CompanyRecoveryRequest
    {
        return CompanyRecoveryRequest::create(['company_id' => $company->id, 'requester_user_id' => $user->id,
            'mode' => 'attach', 'name' => 'Alice', 'phone' => '+237690000000', 'position' => 'Propriétaire',
            'explanation' => 'Je tiens cet atelier près du marché.', 'status' => $status,
            'admin_reply' => $status === 'rejected' ? 'Merci de préciser votre lien.' : null]);
    }

    public function test_cards_use_only_the_authenticated_viewers_latest_request(): void
    {
        $company = $this->company();
        $alice = User::factory()->create();
        $bob = User::factory()->create();
        $this->claim($company, $alice, 'rejected');
        $latest = $this->claim($company, $alice);
        $bobClaim = $this->claim($company, $bob, 'rejected');
        $this->getJson('/api/company-recovery/companies')->assertOk()
            ->assertJsonPath('data.data.0.my_request', null)->assertJsonPath('data.data.0.can_request', true);
        $this->actingAs($alice, 'sanctum')->getJson('/api/company-recovery/companies')->assertOk()
            ->assertJsonPath('data.data.0.my_request.id', $latest->id)
            ->assertJsonPath('data.data.0.my_request.status', 'pending')
            ->assertJsonPath('data.data.0.can_request', false)
            ->assertJsonMissingPath('data.data.0.my_request.requester_user_id');
        $this->actingAs($bob, 'sanctum')->getJson('/api/company-recovery/companies')->assertOk()
            ->assertJsonPath('data.data.0.my_request.id', $bobClaim->id)
            ->assertJsonPath('data.data.0.my_request.status', 'rejected')
            ->assertJsonPath('data.data.0.can_request', true);
    }

    public function test_approved_companies_stay_visible_only_in_the_requesters_catalog(): void
    {
        $company = $this->company();
        $alice = User::factory()->create();
        $this->claim($company, $alice, 'approved');
        $company->forceFill(['recovery_claimed_at' => now(), 'recovery_claimed_by' => $alice->id])->save();
        $this->getJson('/api/company-recovery/companies')->assertJsonCount(0, 'data.data');
        $this->actingAs($alice, 'sanctum')->getJson('/api/company-recovery/companies')->assertOk()
            ->assertJsonPath('data.data.0.my_request.status', 'approved')
            ->assertJsonPath('data.data.0.recovery_available', false)->assertJsonPath('data.data.0.can_request', false);
        $this->getJson('/api/company-recovery/filters')->assertJsonPath('cities.0', 'Douala');
        $this->actingAs(User::factory()->create(), 'sanctum')->getJson('/api/company-recovery/companies')->assertJsonCount(0, 'data.data');
    }

    public function test_company_detail_is_complete_public_and_does_not_leak_claims_or_placeholder_credentials(): void
    {
        $company = $this->company();
        $alice = User::factory()->create();
        $claim = $this->claim($company, $alice);
        $url = '/api/company-recovery/companies/'.$company->id;
        $this->getJson($url)->assertOk()->assertJsonPath('data.description', $company->description)
            ->assertJsonPath('data.photos_urls.0', 'https://example.test/photo.jpg')
            ->assertJsonPath('data.phone', '+237690000000')->assertJsonPath('data.website', 'https://example.test')
            ->assertJsonPath('data.email', null)->assertJsonPath('data.my_request', null)
            ->assertJsonMissingPath('data.recovery_user_id')->assertJsonMissingPath('data.password');
        $this->actingAs($alice, 'sanctum')->getJson($url)->assertOk()->assertJsonPath('data.my_request.id', $claim->id)
            ->assertJsonPath('data.can_request', false)->assertHeader('Cache-Control', 'no-store, private');
        $company->update(['status' => 'suspended']);
        $this->getJson($url)->assertNotFound();
    }
}
