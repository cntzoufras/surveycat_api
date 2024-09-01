<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {

    public function run(): void {

        if (config('app.env') === 'local') {
            // For local environments, seed with 20 random users
            User::factory(20)->create();
        } else {
            // For non-local environments (like production), create a default admin user
            User::updateOrCreate(
                ['email' => env('DEFAULT_ADMIN_EMAIL')],
                [
                    'username' => env('DEFAULT_ADMIN_USERNAME'),
                    'email'    => env('DEFAULT_ADMIN_EMAIL'),
                    'password' => Hash::make(env('DEFAULT_ADMIN_PASSWORD')),
                    'role'     => 'admin',
                ]
            );
        }
    }
}