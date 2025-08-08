<?php

namespace Database\Seeders;

use App\Models\Combo;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComboSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create exactly 5 combos using factory
        Combo::factory(5)->active()->create();

        // Remove the signature combos section since we only want 5 total

        $this->command->info('Created ' . Combo::count() . ' combos successfully.');
    }
}
