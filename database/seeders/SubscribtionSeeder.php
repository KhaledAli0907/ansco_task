<?php

namespace Database\Seeders;

use App\Models\Subscribtion;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubscribtionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user for created_by, updated_by, deleted_by
        $admin = User::where('email', 'admin@anasco.com')->first();

        if (!$admin) {
            $this->command->error('Admin user not found! Please run AdminUserSeeder first.');
            return;
        }

        $subscriptions = [
            [
                'name' => 'Basic Monthly Plan',
                'description' => 'Perfect for individuals getting started. Includes basic features and support.',
                'price' => 99.99,
                'currency' => 'EGP',
                'duration' => 1,
                'duration_type' => 'months',
                'status' => 'active',
            ],
            [
                'name' => 'Professional Monthly Plan',
                'description' => 'Ideal for growing businesses. Includes advanced features, priority support, and analytics.',
                'price' => 199.99,
                'currency' => 'EGP',
                'duration' => 90,
                'duration_type' => 'days',
                'status' => 'active',
            ],
            [
                'name' => 'Enterprise Monthly Plan',
                'description' => 'For large organizations. Includes all features, dedicated support, custom integrations, and SLA guarantee.',
                'price' => 399.99,
                'currency' => 'EGP',
                'duration' => 1,
                'duration_type' => 'years',
                'status' => 'active',
            ],
        ];

        foreach ($subscriptions as $subscription) {
            Subscribtion::create([
                ...$subscription,
                'created_by' => $admin->id,
            ]);
        }

        $this->command->info('Created 3 monthly subscriptions successfully!');
    }
}
