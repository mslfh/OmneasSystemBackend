<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create spice level attributes (specific chili levels from factory)
        Attribute::factory()->spiceLevel()->free()->create([
            'name' => 'chilli 3',
            'type' => 'spice_level'
        ]);
        Attribute::factory()->spiceLevel()->free()->create([
            'name' => 'chilli 5',
            'type' => 'spice_level'
        ]);
        Attribute::factory()->spiceLevel()->free()->create([
            'name' => 'chilli 7',
            'type' => 'spice_level'
        ]);
        Attribute::factory()->spiceLevel()->free()->create([
            'name' => 'chilli 10',
            'type' => 'spice_level'
        ]);

        // Create no spicy attributes
        Attribute::factory()->noSpicy()->create([
            'name' => 'No Spicy',
            'type' => 'no_spicy'
        ]);
        Attribute::factory()->noSpicy()->create([
            'name' => 'No Hot',
            'type' => 'no_spicy'
        ]);
        Attribute::factory()->noSpicy()->create([
            'name' => 'No Chili',
            'type' => 'no_spicy'
        ]);
        Attribute::factory()->noSpicy()->create([
            'name' => 'Mild Only',
            'type' => 'no_spicy'
        ]);

        // Create no vegetables attributes
        Attribute::factory()->noVeg()->create([
            'name' => 'No Vegetables',
            'type' => 'no_veg'
        ]);
        Attribute::factory()->noVeg()->create([
            'name' => 'No Greens',
            'type' => 'no_veg'
        ]);
        Attribute::factory()->noVeg()->create([
            'name' => 'No Onions',
            'type' => 'no_veg'
        ]);
        Attribute::factory()->noVeg()->create([
            'name' => 'No Herbs',
            'type' => 'no_veg'
        ]);

        // Create no meats attributes
        Attribute::factory()->noMeats()->create([
            'name' => 'No Meat',
            'type' => 'no_meats'
        ]);
        Attribute::factory()->noMeats()->create([
            'name' => 'No Beef',
            'type' => 'no_meats'
        ]);
        Attribute::factory()->noMeats()->create([
            'name' => 'No Pork',
            'type' => 'no_meats'
        ]);
        Attribute::factory()->noMeats()->create([
            'name' => 'No Chicken',
            'type' => 'no_meats'
        ]);
        Attribute::factory()->noMeats()->create([
            'name' => 'No Seafood',
            'type' => 'no_meats'
        ]);

        // Create only attributes (with some cost)
        Attribute::factory()->only()->create([
            'name' => 'Cheese Only',
            'type' => 'only'
        ]);
        Attribute::factory()->only()->create([
            'name' => 'Sauce Only',
            'type' => 'only'
        ]);
        Attribute::factory()->only()->create([
            'name' => 'Bread Only',
            'type' => 'only'
        ]);
        Attribute::factory()->only()->create([
            'name' => 'Rice Only',
            'type' => 'only'
        ]);
        Attribute::factory()->only()->create([
            'name' => 'Noodles Only',
            'type' => 'only'
        ]);

        // Create chili attributes
        Attribute::factory()->chili()->create([
            'name' => 'Green Chili',
            'type' => 'chili'
        ]);
        Attribute::factory()->chili()->create([
            'name' => 'Red Chili',
            'type' => 'chili'
        ]);
        Attribute::factory()->chili()->create([
            'name' => 'Bird Eye Chili',
            'type' => 'chili'
        ]);
        Attribute::factory()->chili()->create([
            'name' => 'Bell Pepper',
            'type' => 'chili'
        ]);
        Attribute::factory()->chili()->create([
            'name' => 'Jalapeno',
            'type' => 'chili'
        ]);

        // Create extra attributes (with cost)
        Attribute::factory()->extra()->create([
            'name' => 'Extra Cheese',
            'type' => 'extra'
        ]);
        Attribute::factory()->extra()->create([
            'name' => 'Extra Sauce',
            'type' => 'extra'
        ]);
        Attribute::factory()->extra()->create([
            'name' => 'Extra Meat',
            'type' => 'extra'
        ]);
        Attribute::factory()->extra()->create([
            'name' => 'Extra Vegetables',
            'type' => 'extra'
        ]);
        Attribute::factory()->extra()->create([
            'name' => 'Extra Portion',
            'type' => 'extra'
        ]);

        // Create sauce attributes
        Attribute::factory()->sauce()->create([
            'name' => 'Tomato Sauce',
            'type' => 'sauce'
        ]);
        Attribute::factory()->sauce()->create([
            'name' => 'BBQ Sauce',
            'type' => 'sauce'
        ]);
        Attribute::factory()->sauce()->create([
            'name' => 'Mayo',
            'type' => 'sauce'
        ]);
        Attribute::factory()->sauce()->create([
            'name' => 'Chili Sauce',
            'type' => 'sauce'
        ]);
        Attribute::factory()->sauce()->create([
            'name' => 'Garlic Sauce',
            'type' => 'sauce'
        ]);
        Attribute::factory()->sauce()->create([
            'name' => 'Sweet & Sour',
            'type' => 'sauce'
        ]);

        // Create additional random attributes using factory
        Attribute::factory(10)->create();

        // Create some premium attributes with higher costs
        Attribute::factory(5)->withCost()->create();

        $this->command->info('Created ' . Attribute::count() . ' attributes successfully.');
    }
}
