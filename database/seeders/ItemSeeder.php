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
        // Create specific meat items
        Item::factory()->meat()->create([
            'name' => 'Chicken Breast',
            'description' => 'Premium quality chicken breast, tender and juicy',
            'price' => 8.50
        ]);

        Item::factory()->meat()->create([
            'name' => 'Beef Strips',
            'description' => 'Fresh beef strips, perfect for stir-fry dishes',
            'price' => 12.00
        ]);

        Item::factory()->meat()->create([
            'name' => 'Pork Belly',
            'description' => 'Marbled pork belly with perfect fat ratio',
            'price' => 9.50
        ]);

        Item::factory()->meat()->create([
            'name' => 'Salmon Fillet',
            'description' => 'Atlantic salmon fillet, rich in omega-3',
            'price' => 15.00
        ]);

        Item::factory()->meat()->create([
            'name' => 'Shrimp',
            'description' => 'Fresh jumbo shrimp, sweet and succulent',
            'price' => 10.00
        ]);

        Item::factory()->meat()->create([
            'name' => 'Duck Breast',
            'description' => 'Premium duck breast with crispy skin',
            'price' => 18.00
        ]);

        Item::factory()->meat()->create([
            'name' => 'Lamb Chops',
            'description' => 'Tender lamb chops with herbs',
            'price' => 20.00
        ]);

        // Create specific source/sauce items
        Item::factory()->source()->create([
            'name' => 'Soy Sauce',
            'description' => 'Traditional aged soy sauce',
            'price' => 1.00
        ]);

        Item::factory()->source()->create([
            'name' => 'Oyster Sauce',
            'description' => 'Rich and savory oyster sauce',
            'price' => 1.50
        ]);

        Item::factory()->source()->create([
            'name' => 'Teriyaki Sauce',
            'description' => 'Sweet and savory Japanese-style sauce',
            'price' => 1.50
        ]);

        Item::factory()->source()->create([
            'name' => 'Sweet & Sour Sauce',
            'description' => 'Classic Chinese-style sweet and tangy sauce',
            'price' => 1.50
        ]);

        Item::factory()->source()->create([
            'name' => 'Black Bean Sauce',
            'description' => 'Rich and savory black bean sauce',
            'price' => 2.00
        ]);

        Item::factory()->source()->create([
            'name' => 'XO Sauce',
            'description' => 'Premium seafood-based spicy sauce',
            'price' => 3.50
        ]);

        Item::factory()->source()->create([
            'name' => 'Hoisin Sauce',
            'description' => 'Sweet and tangy Chinese barbecue sauce',
            'price' => 2.00
        ]);

        // Create specific soup items
        Item::factory()->soup()->create([
            'name' => 'Chicken Broth',
            'description' => 'Rich and flavorful chicken bone broth',
            'price' => 4.00
        ]);

        Item::factory()->soup()->create([
            'name' => 'Beef Bone Broth',
            'description' => 'Deep and hearty beef bone broth',
            'price' => 5.00
        ]);

        Item::factory()->soup()->create([
            'name' => 'Vegetable Stock',
            'description' => 'Light and nutritious vegetable broth',
            'price' => 3.00
        ]);

        Item::factory()->soup()->create([
            'name' => 'Miso Soup',
            'description' => 'Traditional Japanese fermented soybean soup',
            'price' => 3.50
        ]);

        Item::factory()->soup()->create([
            'name' => 'Tom Yum Soup',
            'description' => 'Spicy and sour Thai soup with herbs',
            'price' => 5.00
        ]);

        Item::factory()->soup()->create([
            'name' => 'Hot & Sour Soup',
            'description' => 'Traditional Chinese hot and sour soup',
            'price' => 4.50
        ]);

        // Create specific staple items
        Item::factory()->staple()->create([
            'name' => 'Steamed Rice',
            'description' => 'Fluffy jasmine rice, perfectly steamed',
            'price' => 2.50
        ]);

        Item::factory()->staple()->create([
            'name' => 'Fried Rice',
            'description' => 'Wok-fried rice with egg and vegetables',
            'price' => 4.50
        ]);

        Item::factory()->staple()->create([
            'name' => 'Fat Noodles (Udon)',
            'description' => 'Thick Japanese wheat noodles',
            'price' => 4.00
        ]);

        Item::factory()->staple()->create([
            'name' => 'Fat Rice Noodles',
            'description' => 'Wide flat rice noodles for stir-fry',
            'price' => 3.50
        ]);

        Item::factory()->staple()->create([
            'name' => 'Thick Wheat Noodles',
            'description' => 'Hearty thick wheat noodles',
            'price' => 3.50
        ]);

        Item::factory()->staple()->create([
            'name' => 'Thin Egg Noodles',
            'description' => 'Delicate thin egg noodles',
            'price' => 3.50
        ]);

        Item::factory()->staple()->create([
            'name' => 'Angel Hair Noodles',
            'description' => 'Ultra-thin delicate noodles',
            'price' => 4.00
        ]);

        Item::factory()->staple()->create([
            'name' => 'Ramen Noodles',
            'description' => 'Fresh ramen noodles with perfect texture',
            'price' => 4.50
        ]);

        // Create specific vegetable items
        Item::factory()->vegetable()->create([
            'name' => 'Bok Choy',
            'description' => 'Fresh Asian greens with crisp texture',
            'price' => 4.50
        ]);

        Item::factory()->vegetable()->create([
            'name' => 'Chinese Broccoli',
            'description' => 'Tender Chinese broccoli with perfect crunch',
            'price' => 4.00
        ]);

        Item::factory()->vegetable()->create([
            'name' => 'Snow Peas',
            'description' => 'Crisp and sweet snow peas',
            'price' => 3.50
        ]);

        Item::factory()->vegetable()->create([
            'name' => 'Bell Peppers',
            'description' => 'Colorful sweet bell peppers',
            'price' => 3.00
        ]);

        Item::factory()->vegetable()->create([
            'name' => 'Mushrooms',
            'description' => 'Fresh shiitake and button mushrooms',
            'price' => 3.50
        ]);

        Item::factory()->vegetable()->create([
            'name' => 'Bean Sprouts',
            'description' => 'Fresh crunchy bean sprouts',
            'price' => 2.50
        ]);

        Item::factory()->vegetable()->create([
            'name' => 'Cabbage',
            'description' => 'Fresh cabbage leaves',
            'price' => 2.00
        ]);

        Item::factory()->vegetable()->create([
            'name' => 'Carrots',
            'description' => 'Sweet and crunchy carrots',
            'price' => 2.50
        ]);

        // Create additional random items using factory methods
        Item::factory(10)->meat()->create();
        Item::factory(8)->source()->create();
        Item::factory(6)->soup()->create();
        Item::factory(8)->staple()->create();
        Item::factory(12)->vegetable()->create();

        // Create some expensive premium items
        Item::factory(5)->expensive()->create();

        // Create some free items for testing
        Item::factory(3)->free()->create();

        $this->command->info('Created ' . Item::count() . ' items successfully.');
        $this->command->info('Meat items: ' . Item::where('type', 'meat')->count());
        $this->command->info('Source items: ' . Item::where('type', 'source')->count());
        $this->command->info('Soup items: ' . Item::where('type', 'soup')->count());
        $this->command->info('Staple items: ' . Item::where('type', 'staple')->count());
        $this->command->info('Vegetable items: ' . Item::where('type', 'vegetable')->count());
    }
}
