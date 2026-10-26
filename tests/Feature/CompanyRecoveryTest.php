<?php

namespace Tests\Feature;

use App\Jobs\SendCompanyRecoveryDelivery;
use App\Mail\CompanyRecoveryMail;
use App\Models\Company;
use App\Models\CompanyRecoveryDelivery;
use App\Models\CompanyRecoveryRequest;
use App\Models\EmailVerification;
use App\Models\Notification;
use App\Models\Recruiter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CompanyRecoveryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        Mail::fake();
        config(['company_recovery.mailer' => 'array']);
    }

    private function imported(): array
    {
        $company = Company::create(['name' => 'Atelier de quartier', 'email' => uniqid().'@example.test',
            'city' => 'Douala', 'sector' => 'Artisanat', 'status' => 'verified', 'latitude' => 4.05, 'longitude' => 9.7]);
        $owner = User::factory()->create(['role' => 'recruiter', 'email_verified_at' => null,
            'must_change_password' => true, 'current_company_id' => $company->id]);
        Recruiter::create(['user_id' => $owner->id, 'company_id' => $company->id, 'can_modify_company' => true]);
        $company->forceFill(['recovery_source' => 'test_import', 'recovery_user_id' => $owner->id])->save();
        return [$company, $owner];
    }

    private function payload(Company $company): array
    {
        return ['company_id' => $company->id, 'name' => 'Alice', 'phone' => '+237690000000',
            'position' => 'Propriétaire', 'explanation' => 'Je tiens cet atelier à Douala depuis cinq ans. Vous pouvez me joindre pour vérifier.'];
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_super_admin' => true]);
    }

    private function guestClaim(Company $company, string $email = 'alice@example.test'): CompanyRecoveryRequest
    {
        $response = $this->postJson('/api/company-recovery/email-code', ['company_id' => $company->id, 'email' => $email])->assertOk();
        $code = null;
        Mail::assertSent(CompanyRecoveryMail::class, function ($mail) use (&$code, $email) {
            if ($mail->hasTo($email) && isset($mail->details['code'])) { $code = $mail->details['code']; return true; }
            return false;
        });
        $created = $this->postJson('/api/company-recovery/account-requests', $this->payload($company) + [
            'reply_email' => $email, 'challenge_id' => $response->json('challenge_id'), 'code' => $code,
        ])->assertCreated();
        return CompanyRecoveryRequest::findOrFail($created->json('data.id'));
    }

    public function test_public_catalog_only_returns_eligible_public_data_and_filters(): void
    {
        [$company] = $this->imported();
        Company::create(['name' => 'Already managed', 'email' => 'managed@example.test', 'status' => 'verified']);
        $this->getJson('/api/company-recovery/companies?city=Douala&q=Atelier')->assertOk()
            ->assertJsonCount(1, 'data.data')->assertJsonPath('data.data.0.id', $company->id)
            ->assertJsonMissingPath('data.data.0.email')->assertJsonMissingPath('data.data.0.recovery_user_id');
        $this->getJson('/api/company-recovery/filters')->assertOk()->assertJsonPath('cities.0', 'Douala');
        $this->getJson('/api/company-recovery/requests')->assertUnauthorized();
        $this->postJson('/api/company-recovery/requests', $this->payload($company))->assertUnauthorized();
    }

    public function test_translated_domain_filters_keep_the_canonical_search_value(): void
    {
        [$company] = $this->imported();
        $category = \App\Models\CompanyCategory::create(['code' => 'recovery-craft', 'level_1' => 'Artisanat', 'is_active' => true]);
        $category->setTranslation('level_1', 'en', 'Crafts');
        $company->categories()->attach($category->id);
        $this->withHeader('Accept-Language', 'en')->getJson('/api/company-recovery/filters')->assertOk()
            ->assertJsonPath('domains.0.value', 'Artisanat')->assertJsonPath('domains.0.label', 'Crafts');
        $this->getJson('/api/company-recovery/companies?domain=Artisanat')->assertOk()->assertJsonCount(1, 'data.data');
    }

    public function test_attach_approval_preserves_existing_user_and_rejects_competing_requests(): void
    {
        [$company, $placeholder] = $this->imported();
        $alice = User::factory()->create();
        $bob = User::factory()->create();
        $oldPassword = $alice->password;
        $claim = $this->actingAs($alice, 'sanctum')->postJson('/api/company-recovery/requests', $this->payload($company))
            ->assertCreated()->json('data.id');
        $this->postJson('/api/company-recovery/requests', $this->payload($company))->assertUnprocessable();
        $this->actingAs($bob, 'sanctum')->getJson('/api/company-recovery/requests')->assertJsonCount(0, 'data.data');
        $other = $this->postJson('/api/company-recovery/requests', $this->payload($company))->assertCreated()->json('data.id');
        $admin = $this->admin();
        $this->actingAs($admin)->get('/admin/company-recoveries')->assertOk()->assertSee('autre(s) demande(s)');
        $this->post('/admin/company-recoveries/'.$claim.'/review', ['decision' => 'approved', 'verified' => '1'])
            ->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame($oldPassword, $alice->fresh()->password);
        $this->assertSame($alice->id, $company->fresh()->recovery_claimed_by);
        $this->assertFalse($placeholder->fresh()->is_active);
        $this->assertDatabaseMissing('recruiters', ['user_id' => $placeholder->id]);
        $this->assertDatabaseHas('recruiters', ['user_id' => $alice->id, 'company_id' => $company->id, 'can_modify_company' => true]);
        $this->assertDatabaseHas('company_recovery_requests', ['id' => $other, 'status' => 'rejected']);
        $this->assertSame(2, Notification::where('type', 'company_recovery_status')->count());
        $this->post('/admin/company-recoveries/'.$other.'/review', ['decision' => 'approved', 'verified' => '1'])->assertSessionHasErrors();
        $this->assertSame($alice->id, $company->fresh()->recovery_claimed_by);
    }

    public function test_guest_email_code_is_scoped_limited_and_never_exposed(): void
    {
        [$company] = $this->imported();
        $challenge = $this->postJson('/api/company-recovery/email-code', ['company_id' => $company->id, 'email' => 'test@example.test'])
            ->assertOk()->assertJsonMissingPath('code')->json('challenge_id');
        $row = DB::table('company_recovery_email_challenges')->where('id', $challenge)->first();
        $this->assertSame(64, strlen($row->code_hash));
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/company-recovery/account-requests', $this->payload($company) + [
                'reply_email' => 'test@example.test', 'challenge_id' => $challenge, 'code' => '000000',
            ])->assertUnprocessable();
        }
        $this->assertSame(5, (int) DB::table('company_recovery_email_challenges')->where('id', $challenge)->value('attempts'));
        $this->assertSame(0, CompanyRecoveryRequest::count());
    }

    public function test_guest_approval_generates_encrypted_credentials_and_forces_password_change(): void
    {
        [$company, $owner] = $this->imported();
        $claim = $this->guestClaim($company);
        $this->actingAs($this->admin())->post('/admin/company-recoveries/'.$claim->id.'/review', [
            'decision' => 'approved', 'verified' => '1', 'admin_reply' => 'Lien avec l’entreprise confirmé par appel.',
        ])->assertSessionHasNoErrors();
        $owner->refresh();
        $this->assertSame('alice@example.test', $owner->email);
        $this->assertNull($owner->phone);
        $this->assertTrue($owner->must_change_password);
        $delivery = CompanyRecoveryDelivery::where('request_id', $claim->id)->where('channel', 'mail')->latest('id')->firstOrFail();
        $password = $delivery->payload['password'];
        $this->assertTrue(Hash::check($password, $owner->password));
        $this->assertStringNotContainsString($password, $delivery->getRawOriginal('payload'));
        (new SendCompanyRecoveryDelivery($delivery->id))->handle();
        $this->assertNotNull($delivery->fresh()->sent_at);
        $this->assertNull($delivery->fresh()->payload);
        Mail::assertSent(CompanyRecoveryMail::class, fn ($mail) => ($mail->details['password'] ?? null) === $password);
        $login = $this->postJson('/api/login', ['identifier' => $owner->email, 'password' => $password, 'device_id' => 'alice-device'])
            ->assertOk()->assertJsonPath('must_change_password', true);
        $this->actingAs($owner, 'sanctum')->getJson('/api/company-recovery/requests')->assertForbidden();
        $this->postJson('/api/password/force-change', [
            'old_password' => $password, 'new_password' => 'MyNewPassword123!', 'new_password_confirmation' => 'MyNewPassword123!',
        ])->assertOk();
        $this->assertFalse($owner->fresh()->must_change_password);
        $this->assertNull($owner->fresh()->temporary_password_expires_at);
        $this->actingAs($this->admin())->post('/admin/company-recoveries/'.$claim->id.'/resend')->assertSessionHasErrors();
    }

    public function test_used_or_multi_company_accounts_are_never_transferred(): void
    {
        [$company, $owner] = $this->imported();
        $alice = User::factory()->create();
        $id = $this->actingAs($alice, 'sanctum')->postJson('/api/company-recovery/requests', $this->payload($company))->assertCreated()->json('data.id');
        $owner->update(['last_login_at' => now()]);
        $this->actingAs($this->admin())->post('/admin/company-recoveries/'.$id.'/review', ['decision' => 'approved', 'verified' => '1'])->assertSessionHasErrors();
        $this->assertNull($company->fresh()->recovery_claimed_at);
        $this->assertDatabaseHas('company_recovery_requests', ['id' => $id, 'status' => 'pending']);
    }

    public function test_admin_permissions_and_refusal_reason_are_required(): void
    {
        [$company] = $this->imported();
        $id = $this->actingAs(User::factory()->create(), 'sanctum')->postJson('/api/company-recovery/requests', $this->payload($company))->json('data.id');
        $url = '/admin/company-recoveries/'.$id.'/review';
        $this->actingAs(User::factory()->create(['role' => 'recruiter', 'permissions' => ['manage_companies']]))
            ->post($url, ['decision' => 'approved', 'verified' => '1'])->assertForbidden();
        $this->actingAs($this->admin())->post($url, ['decision' => 'approved'])->assertSessionHasErrors('verified');
        $this->post($url, ['decision' => 'rejected'])->assertSessionHasErrors('admin_reply');
        $this->post($url, ['decision' => 'rejected', 'admin_reply' => 'Impossible de confirmer votre lien.'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('company_recovery_requests', ['id' => $id, 'status' => 'rejected']);
    }

    public function test_reset_requires_individual_single_use_proof_and_revokes_tokens(): void
    {
        $user = User::factory()->create();
        $user->createToken('old-session');
        EmailVerification::create(['email' => $user->email, 'code' => '123456', 'verified' => false, 'expires_at' => now()->addMinutes(5)]);
        $verified = $this->postJson('/api/otp/password-reset/verify', ['email' => $user->email, 'code' => '123456'])->assertOk();
        $data = ['identifier' => $user->email, 'password' => 'SecureNewPassword123!', 'password_confirmation' => 'SecureNewPassword123!'];
        $this->postJson('/api/password/reset', $data)->assertUnprocessable();
        $this->postJson('/api/password/reset', $data + ['token' => str_repeat('x', 64)])->assertUnprocessable();
        $this->postJson('/api/password/reset', $data + ['token' => $verified->json('reset_token')])->assertOk();
        $this->assertSame(0, $user->tokens()->count());
        $this->postJson('/api/password/reset', $data + ['token' => $verified->json('reset_token')])->assertUnprocessable();
    }

    public function test_inactive_and_expired_temporary_accounts_cannot_login(): void
    {
        $user = User::factory()->create(['password' => Hash::make('Temporary123!'), 'must_change_password' => true]);
        $user->forceFill(['temporary_password_expires_at' => now()->subMinute()])->save();
        $body = ['identifier' => $user->email, 'password' => 'Temporary123!', 'device_id' => 'device'];
        $this->postJson('/api/login', $body)->assertForbidden();
        $user->update(['is_active' => false]);
        $this->postJson('/api/login', $body)->assertUnauthorized();
    }

    public function test_code_cannot_be_replayed_or_used_for_another_company(): void
    {
        [$company] = $this->imported();
        [$other] = $this->imported();
        $challenge = $this->postJson('/api/company-recovery/email-code', ['company_id' => $company->id, 'email' => 'replay@example.test'])->json('challenge_id');
        $code = null;
        Mail::assertSent(CompanyRecoveryMail::class, function ($mail) use (&$code) { $code = $mail->details['code']; return true; });
        $data = ['reply_email' => 'replay@example.test', 'challenge_id' => $challenge, 'code' => $code];
        $this->postJson('/api/company-recovery/account-requests', $this->payload($other) + $data)->assertUnprocessable();
        $this->postJson('/api/company-recovery/account-requests', $this->payload($company) + $data)->assertCreated();
        $this->postJson('/api/company-recovery/account-requests', $this->payload($company) + $data)->assertUnprocessable();
        $this->assertSame(1, CompanyRecoveryRequest::count());
    }

    public function test_recovery_rechecks_email_collision_and_other_company_memberships_at_approval(): void
    {
        [$company, $owner] = $this->imported();
        $claim = $this->guestClaim($company);
        User::factory()->create(['email' => 'alice@example.test']);
        $url = '/admin/company-recoveries/'.$claim->id.'/review';
        $this->actingAs($this->admin())->post($url, ['decision' => 'approved', 'verified' => '1'])->assertSessionHasErrors();
        $this->assertNull($company->fresh()->recovery_claimed_at);
        $other = Company::create(['name' => 'Autre entreprise', 'email' => 'other-company@example.test', 'status' => 'verified']);
        Recruiter::create(['user_id' => $owner->id, 'company_id' => $other->id]);
        $this->post($url, ['decision' => 'approved', 'verified' => '1'])->assertSessionHasErrors();
        $this->assertNull($company->fresh()->recovery_claimed_at);
    }

    public function test_delivery_failure_is_retryable_and_regeneration_invalidates_old_credentials(): void
    {
        [$company, $owner] = $this->imported();
        $claim = $this->guestClaim($company);
        $admin = $this->admin();
        $this->actingAs($admin)->post('/admin/company-recoveries/'.$claim->id.'/review', ['decision' => 'approved', 'verified' => '1'])->assertSessionHasNoErrors();
        $delivery = CompanyRecoveryDelivery::where('request_id', $claim->id)->where('channel', 'mail')->latest('id')->firstOrFail();
        $oldPassword = $delivery->payload['password'];
        Mail::shouldReceive('mailer')->andThrow(new \RuntimeException('Secret transport details'));
        try { (new SendCompanyRecoveryDelivery($delivery->id))->handle(); $this->fail('Expected delivery failure'); }
        catch (\RuntimeException $e) { $this->assertStringNotContainsString('Secret', $e->getMessage()); }
        $this->assertNull($delivery->fresh()->sent_at);
        $this->assertNotNull($delivery->fresh()->payload);
        Mail::swap(new \Illuminate\Mail\MailManager($this->app));
        Mail::fake();
        (new SendCompanyRecoveryDelivery($delivery->id))->handle();
        (new SendCompanyRecoveryDelivery($delivery->id))->handle();
        Mail::assertSent(CompanyRecoveryMail::class, 1);
        $this->assertNull($delivery->fresh()->payload);
        $this->actingAs($admin)->post('/admin/company-recoveries/'.$claim->id.'/resend')->assertSessionHasNoErrors();
        $this->assertFalse(Hash::check($oldPassword, $owner->fresh()->password));
        $new = CompanyRecoveryDelivery::where('request_id', $claim->id)->where('channel', 'mail')->latest('id')->firstOrFail();
        $this->assertTrue(Hash::check($new->payload['password'], $owner->fresh()->password));
        $this->travel(49)->hours();
        (new SendCompanyRecoveryDelivery($new->id))->handle();
        $this->assertNotNull($new->fresh()->cancelled_at);
        $this->assertNull($new->fresh()->payload);
    }

    public function test_temporary_password_cannot_edit_profile_or_bypass_restriction_using_user_prefix(): void
    {
        $user = User::factory()->create(['must_change_password' => true]);
        $this->actingAs($user, 'sanctum')->putJson('/api/user/profile', ['name' => 'Changed'])->assertForbidden();
        $this->getJson('/api/user')->assertOk();
        $this->assertNotSame('Changed', $user->fresh()->name);
    }
}
