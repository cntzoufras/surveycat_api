<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {

    public function run(): void {
        // Read admin credentials from config (works with cached config)
        $defaultEmail = config('app.admin.default_email');
        $defaultUsername = config('app.admin.default_username');
        $defaultPassword = config('app.admin.default_password');

        // Basic validation to avoid NOT NULL violations
        if (empty($defaultEmail) || empty($defaultUsername) || empty($defaultPassword)) {
            // Provide a clear message during seeding
            if (isset($this->command)) {
                $this->command->warn('UserSeeder: DEFAULT_ADMIN_* variables are missing. Skipping admin user creation.');
            }
            return;
        }

        // Create or update the admin user (works in all environments)
        User::updateOrCreate(
            ['email' => $defaultEmail],
            [
                'username'          => $defaultUsername,
                'email'             => $defaultEmail,
                'password'          => Hash::make($defaultPassword),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Also seed some sample users for demo data in modules (all environments)
        User::factory(20)->create();
    }
}