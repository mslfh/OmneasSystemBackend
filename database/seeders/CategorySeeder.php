<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create main categories using factory
        $mealCategory = Category::factory()->active()->create([
            'title' => 'Meal',
            'hint' => 'Traditional flavors perfect for everyone',
        ]);

        $drinkCategory = Category::factory()->active()->create([
            'title' => 'Drink',
            'hint' => 'Light and refreshing, perfect for health-conscious diners',
        ]);

        $soupCategory = Category::factory()->active()->create([
            'title' => 'Soup',
            'hint' => 'Healthy and nutritious ingredients sourced fresh daily',
        ]);

        $snackCategory = Category::factory()->active()->create([
            'title' => 'Snack',
            'hint' => 'Sweet and delightful, loved by all ages',
        ]);

        // Create subcategories using factory with proper parent relationships
        // Meal subcategories
        Category::factory()->subcategory($mealCategory->id)->active()->create([
            'title' => 'Noodle',
            'hint' => 'Delicious noodle dishes with rich flavors',
        ]);

        Category::factory()->subcategory($mealCategory->id)->active()->create([
            'title' => 'Fry Rice',
            'hint' => 'Perfectly seasoned fried rice variations',
        ]);

        // Drink subcategories
        Category::factory()->subcategory($drinkCategory->id)->active()->create([
            'title' => '200ml Tin',
            'hint' => 'Convenient single-serving beverages',
        ]);

        Category::factory()->subcategory($drinkCategory->id)->active()->create([
            'title' => '600ml bottle',
            'hint' => 'Large sharing size beverages',
        ]);

        // Create some additional random main categories
        Category::factory(2)->active()->create();

        // Create some additional subcategories
        $parentCategories = Category::whereNull('parent_id')->pluck('id')->toArray();
        foreach ($parentCategories as $parentId) {
            if (rand(1, 3) == 1) { // 33% chance to create additional subcategory
                Category::factory()->subcategory($parentId)->create();
            }
        }

        // Create some inactive categories for testing
        Category::factory(2)->inactive()->create();

        $this->command->info('Created ' . Category::count() . ' categories successfully.');
        $this->command->info('Main categories: ' . Category::whereNull('parent_id')->count());
        $this->command->info('Sub categories: ' . Category::whereNotNull('parent_id')->count());
    }
}
