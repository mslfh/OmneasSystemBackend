<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create exactly 3 specific restaurant profiles
        $specificProfiles = [
            [
                'name' => 'Business Dining Profile',
                'description' => 'Professional dining configuration for business meetings and corporate events',
                'status' => 'active',
                'settings' => [
                    'max_items' => 8,
                    'discount_rate' => 0.15,
                    'allow_customization' => true,
                    'priority_level' => 5,
                    'time_slots' => ['morning', 'afternoon', 'evening'],
                    'cuisine_type' => 'Western',
                ]
            ],
            [
                'name' => 'Family Dining Profile',
                'description' => 'Family-friendly meal setup perfect for group dining and celebrations',
                'status' => 'active',
                'settings' => [
                    'max_items' => 10,
                    'discount_rate' => 0.20,
                    'allow_customization' => true,
                    'priority_level' => 4,
                    'time_slots' => ['afternoon', 'evening'],
                    'cuisine_type' => 'Chinese',
                ]
            ],
            [
                'name' => 'Quick Service Profile',
                'description' => 'Fast and efficient service profile for quick meals and takeaway orders',
                'status' => 'active',
                'settings' => [
                    'max_items' => 4,
                    'discount_rate' => 0.08,
                    'allow_customization' => false,
                    'priority_level' => 3,
                    'time_slots' => ['afternoon'],
                    'cuisine_type' => 'Fast Food',
                ]
            ]
        ];

        foreach ($specificProfiles as $profile) {
            Profile::create($profile);
        }

        $this->command->info('Created ' . Profile::count() . ' profiles successfully.');
    }
}
