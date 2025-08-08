<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create main ingredients
        Item::factory()->ingredient()->create([
            'name' => 'Chicken Breast',
            'type' => 'ingredient',
            'description' => 'Premium quality chicken breast, tender and juicy',
            'price' => 8.50
        ]);

        Item::factory()->ingredient()->create([
            'name' => 'Beef Strips',
            'type' => 'ingredient',
            'description' => 'Fresh beef strips, perfect for stir-fry dishes',
            'price' => 12.00
        ]);

        Item::factory()->ingredient()->create([
            'name' => 'Salmon Fillet',
            'type' => 'ingredient',
            'description' => 'Atlantic salmon fillet, rich in omega-3',
            'price' => 15.00
        ]);

        Item::factory()->ingredient()->create([
            'name' => 'Shrimp',
            'type' => 'ingredient',
            'description' => 'Fresh jumbo shrimp, sweet and succulent',
            'price' => 10.00
        ]);

        Item::factory()->ingredient()->create([
            'name' => 'Pork Belly',
            'type' => 'ingredient',
            'description' => 'Marbled pork belly with perfect fat ratio',
            'price' => 9.50
        ]);

        // Create vegetables
        Item::factory()->ingredient()->create([
            'name' => 'Mixed Vegetables',
            'type' => 'ingredient',
            'description' => 'Fresh seasonal vegetables mix',
            'price' => 4.50
        ]);

        Item::factory()->ingredient()->create([
            'name' => 'Mushrooms',
            'type' => 'ingredient',
            'description' => 'Fresh shiitake and button mushrooms',
            'price' => 3.50
        ]);

        // Create popular sauces
        Item::factory()->sauce()->create([
            'name' => 'Teriyaki Sauce',
            'type' => 'sauce',
            'description' => 'Sweet and savory Japanese-style sauce',
            'price' => 1.50
        ]);

        Item::factory()->sauce()->create([
            'name' => 'Sweet & Sour Sauce',
            'type' => 'sauce',
            'description' => 'Classic Chinese-style sweet and tangy sauce',
            'price' => 1.50
        ]);

        Item::factory()->sauce()->create([
            'name' => 'Black Bean Sauce',
            'type' => 'sauce',
            'description' => 'Rich and savory black bean sauce',
            'price' => 2.00
        ]);

        Item::factory()->sauce()->create([
            'name' => 'Garlic Sauce',
            'type' => 'sauce',
            'description' => 'Aromatic garlic-infused sauce',
            'price' => 1.50
        ]);

        Item::factory()->sauce()->create([
            'name' => 'Spicy Chili Sauce',
            'type' => 'sauce',
            'description' => 'Hot and spicy chili sauce for heat lovers',
            'price' => 1.00
        ]);

        // Create popular addons
        Item::factory()->addon()->create([
            'name' => 'Extra Cheese',
            'type' => 'addon',
            'description' => 'Premium melted cheese topping',
            'price' => 2.50
        ]);

        Item::factory()->addon()->create([
            'name' => 'Fried Egg',
            'type' => 'addon',
            'description' => 'Perfect sunny-side up egg',
            'price' => 2.00
        ]);

        Item::factory()->addon()->create([
            'name' => 'Avocado',
            'type' => 'addon',
            'description' => 'Fresh sliced avocado',
            'price' => 3.50
        ]);

        Item::factory()->addon()->create([
            'name' => 'Bacon Bits',
            'type' => 'addon',
            'description' => 'Crispy bacon pieces',
            'price' => 3.00
        ]);

        Item::factory()->addon()->create([
            'name' => 'Extra Meat',
            'type' => 'addon',
            'description' => 'Double portion of your chosen protein',
            'price' => 6.00
        ]);

        // Create side dishes
        Item::factory()->create([
            'name' => 'Steamed Rice',
            'type' => 'side_dish',
            'description' => 'Fluffy jasmine rice, perfectly steamed',
            'price' => 2.50
        ]);

        Item::factory()->create([
            'name' => 'Fried Rice',
            'type' => 'side_dish',
            'description' => 'Wok-fried rice with egg and vegetables',
            'price' => 4.50
        ]);

        Item::factory()->create([
            'name' => 'French Fries',
            'type' => 'side_dish',
            'description' => 'Golden crispy potato fries',
            'price' => 3.50
        ]);

        Item::factory()->create([
            'name' => 'Garden Salad',
            'type' => 'side_dish',
            'description' => 'Fresh mixed greens with house dressing',
            'price' => 4.00
        ]);

        // Create beverages
        Item::factory()->create([
            'name' => 'Green Tea',
            'type' => 'beverage',
            'description' => 'Traditional Chinese green tea',
            'price' => 2.00
        ]);

        Item::factory()->create([
            'name' => 'Fresh Orange Juice',
            'type' => 'beverage',
            'description' => 'Freshly squeezed orange juice',
            'price' => 3.50
        ]);

        Item::factory()->create([
            'name' => 'Soft Drink',
            'type' => 'beverage',
            'description' => 'Choice of cola, sprite, or other sodas',
            'price' => 2.50
        ]);

        // Create condiments and seasonings
        Item::factory()->free()->create([
            'name' => 'Soy Sauce',
            'type' => 'condiment',
            'description' => 'Premium light soy sauce',
            'price' => 0.00
        ]);

        Item::factory()->free()->create([
            'name' => 'Chili Flakes',
            'type' => 'condiment',
            'description' => 'Dried red chili flakes for extra heat',
            'price' => 0.00
        ]);

        Item::factory()->free()->create([
            'name' => 'Sesame Oil',
            'type' => 'condiment',
            'description' => 'Aromatic sesame oil for finishing',
            'price' => 0.00
        ]);

        // Create garnishes
        Item::factory()->create([
            'name' => 'Green Onions',
            'type' => 'garnish',
            'description' => 'Fresh chopped green onions',
            'price' => 0.50
        ]);

        Item::factory()->create([
            'name' => 'Cilantro',
            'type' => 'garnish',
            'description' => 'Fresh cilantro leaves',
            'price' => 0.50
        ]);

        Item::factory()->create([
            'name' => 'Sesame Seeds',
            'type' => 'garnish',
            'description' => 'Toasted sesame seeds',
            'price' => 0.50
        ]);

        // Create additional random items using factory
        Item::factory(20)->create();

        // Create some expensive premium items
        Item::factory(8)->expensive()->create();

        // Create more specific type items
        Item::factory(5)->ingredient()->create();
        Item::factory(5)->sauce()->create();
        Item::factory(5)->addon()->create();
    }
}
