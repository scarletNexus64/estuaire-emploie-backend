<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@estuaire-emploie.com'],
            [
                'name' => 'Super Admin',
                'role' => 'admin',
                'is_super_admin' => true,
                'is_active' => true,
                'password' => Hash::make('password'), // Change this in production
                'permissions' => [], // Super admin doesn't need specific permissions
            ]
        );

        $this->command->info('Super Admin created/updated successfully!');
        $this->command->info('Email: ' . $superAdmin->email);
        $this->command->warn('Default password: password (Please change this in production!)');
    }
}
