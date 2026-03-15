<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@helpdesk.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create agent users
        User::create([
            'name' => 'Support Agent',
            'email' => 'agent@helpdesk.test',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Jane Agent',
            'email' => 'jane.agent@helpdesk.test',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'email_verified_at' => now(),
        ]);

        // Create regular users
        User::factory()->count(5)->create();
    }
}
