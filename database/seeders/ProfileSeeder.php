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
        // Create exactly 1 specific restaurant profiles
        $specificProfiles = [
            [
                'name' => 'Tax Profile',
                'description' => 'Specific tax configuration for products',
                'type' => 'tax',
                'status' => 'active',
                'settings' => [
                    'tax_rate' => 10.0,
                ]
            ],
        ];

        foreach ($specificProfiles as $profile) {
            Profile::create($profile);
        }

        $this->command->info('Created ' . Profile::count() . ' profiles successfully.');
    }
}
