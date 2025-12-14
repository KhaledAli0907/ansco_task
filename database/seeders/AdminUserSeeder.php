<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@anasco.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('Passw0rd!'),
                'role' => 'admin',
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@anasco.com');
            $this->command->info('Password: Passw0rd!');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
