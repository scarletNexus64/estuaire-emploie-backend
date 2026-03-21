<?php

namespace Database\Seeders;

use App\Models\Recruiter;
use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;

class RecruiterSeeder extends Seeder
{
    public function run(): void
    {
        $recruiters = User::where('role', 'recruiter')->get();
        $companies = Company::where('status', 'verified')->get();

        foreach ($recruiters as $index => $recruiter) {
            $company = $companies[$index % $companies->count()];

            Recruiter::create([
                'user_id' => $recruiter->id,
                'company_id' => $company->id,
                'position' => ['RH Manager', 'Talent Acquisition', 'Recruteur Senior'][array_rand(['RH Manager', 'Talent Acquisition', 'Recruteur Senior'])],
                'can_publish' => true,
                'can_view_applications' => true,
                'can_modify_company' => $index === 0,
            ]);
        }
    }
}
