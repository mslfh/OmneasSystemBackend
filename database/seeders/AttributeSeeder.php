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
        // Create size attributes (no extra cost)
        Attribute::factory()->size()->free()->create(['name' => 'Small', 'type' => 'size']);
        Attribute::factory()->size()->create(['name' => 'Medium', 'type' => 'size', 'extra_cost' => 2.00]);
        Attribute::factory()->size()->create(['name' => 'Large', 'type' => 'size', 'extra_cost' => 5.00]);
        Attribute::factory()->size()->create(['name' => 'Extra Large', 'type' => 'size', 'extra_cost' => 8.00]);

        // Create spice level attributes (no extra cost)
        Attribute::factory()->spiceLevel()->free()->create(['name' => 'No Spice', 'type' => 'spice_level']);
        Attribute::factory()->spiceLevel()->free()->create(['name' => 'Mild', 'type' => 'spice_level']);
        Attribute::factory()->spiceLevel()->free()->create(['name' => 'Medium', 'type' => 'spice_level']);
        Attribute::factory()->spiceLevel()->free()->create(['name' => 'Spicy', 'type' => 'spice_level']);
        Attribute::factory()->spiceLevel()->free()->create(['name' => 'Extra Spicy', 'type' => 'spice_level']);

        // Create temperature attributes (no extra cost)
        Attribute::factory()->free()->create(['name' => 'Hot', 'type' => 'temperature']);
        Attribute::factory()->free()->create(['name' => 'Cold', 'type' => 'temperature']);
        Attribute::factory()->free()->create(['name' => 'Warm', 'type' => 'temperature']);
        Attribute::factory()->free()->create(['name' => 'Room Temperature', 'type' => 'temperature']);

        // Create cooking method attributes (some with extra cost)
        Attribute::factory()->free()->create(['name' => 'Grilled', 'type' => 'cooking_method']);
        Attribute::factory()->free()->create(['name' => 'Fried', 'type' => 'cooking_method']);
        Attribute::factory()->free()->create(['name' => 'Steamed', 'type' => 'cooking_method']);
        Attribute::factory()->create(['name' => 'Roasted', 'type' => 'cooking_method', 'extra_cost' => 3.00]);
        Attribute::factory()->create(['name' => 'Smoked', 'type' => 'cooking_method', 'extra_cost' => 5.00]);

        // Create dietary attributes (no extra cost)
        Attribute::factory()->dietary()->create(['name' => 'Vegetarian', 'type' => 'dietary']);
        Attribute::factory()->dietary()->create(['name' => 'Vegan', 'type' => 'dietary']);
        Attribute::factory()->dietary()->create(['name' => 'Gluten-Free', 'type' => 'dietary']);
        Attribute::factory()->dietary()->create(['name' => 'Sugar-Free', 'type' => 'dietary']);
        Attribute::factory()->dietary()->create(['name' => 'Low-Fat', 'type' => 'dietary']);
        Attribute::factory()->dietary()->create(['name' => 'High-Protein', 'type' => 'dietary']);

        // Create sweetness level attributes (no extra cost)
        Attribute::factory()->free()->create(['name' => 'No Sugar', 'type' => 'sweetness']);
        Attribute::factory()->free()->create(['name' => 'Low Sugar', 'type' => 'sweetness']);
        Attribute::factory()->free()->create(['name' => 'Medium Sweet', 'type' => 'sweetness']);
        Attribute::factory()->free()->create(['name' => 'Sweet', 'type' => 'sweetness']);
        Attribute::factory()->free()->create(['name' => 'Extra Sweet', 'type' => 'sweetness']);

        // Create portion attributes (with extra cost for larger portions)
        Attribute::factory()->free()->create(['name' => 'Individual', 'type' => 'portion']);
        Attribute::factory()->create(['name' => 'Double', 'type' => 'portion', 'extra_cost' => 6.00]);
        Attribute::factory()->create(['name' => 'Family Size', 'type' => 'portion', 'extra_cost' => 12.00]);
        Attribute::factory()->create(['name' => 'Sharing', 'type' => 'portion', 'extra_cost' => 8.00]);

        // Create texture attributes (no extra cost)
        Attribute::factory()->free()->create(['name' => 'Crispy', 'type' => 'texture']);
        Attribute::factory()->free()->create(['name' => 'Soft', 'type' => 'texture']);
        Attribute::factory()->free()->create(['name' => 'Chewy', 'type' => 'texture']);
        Attribute::factory()->free()->create(['name' => 'Creamy', 'type' => 'texture']);

        // Create additional random attributes using factory
        Attribute::factory(15)->create();

        // Create some premium attributes with higher costs
        Attribute::factory(5)->withCost()->create();
    }
}
