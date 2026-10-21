<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyReview;
use App\Models\Recruiter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyReviewTest extends TestCase
{
    use RefreshDatabase;

    private function company(): Company
    {
        return Company::create([
            'name' => 'Entreprise test',
            'email' => 'reviews@example.test',
            'sector' => 'Services',
            'status' => 'verified',
        ]);
    }

    public function test_reviews_are_public_and_paginated_with_summary(): void
    {
        $company = $this->company();
        foreach ([5, 4, 3] as $rating) {
            CompanyReview::create([
                'company_id' => $company->id,
                'user_id' => User::factory()->create()->id,
                'rating' => $rating,
                'comment' => 'Très bon service',
            ]);
        }

        $response = $this->getJson("/api/companies/{$company->id}/reviews?per_page=2&page=2")
            ->assertOk()
            ->assertJsonPath('data.current_page', 2)
            ->assertJsonPath('data.last_page', 2)
            ->assertJsonPath('summary.review_count', 3)
            ->assertJsonPath('summary.average_rating', 4);

        $this->assertCount(1, $response->json('data.data'));
        $this->assertArrayNotHasKey('email', $response->json('data.data.0.author'));
    }

    public function test_authentication_validation_and_one_review_per_user(): void
    {
        $company = $this->company();
        $url = "/api/companies/{$company->id}/reviews";
        $this->postJson($url, ['rating' => 5, 'comment' => 'Très bien'])->assertUnauthorized();

        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum')
            ->postJson($url, ['rating' => 0, 'comment' => 'ok'])
            ->assertUnprocessable();
        $this->postJson($url, ['rating' => 5, 'comment' => 'Très bien'])
            ->assertCreated();
        $this->postJson($url, ['rating' => 3, 'comment' => 'Avis mis à jour'])
            ->assertOk();

        $this->assertSame(1, CompanyReview::where('company_id', $company->id)->count());
        $this->assertSame(3, CompanyReview::first()->rating);
    }

    public function test_company_recruiter_cannot_review_own_company(): void
    {
        $company = $this->company();
        $user = User::factory()->recruiter()->create();
        Recruiter::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'position' => 'Manager',
        ]);

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/companies/{$company->id}/reviews", [
                'rating' => 5,
                'comment' => 'Notre entreprise',
            ])
            ->assertForbidden();
    }
}
