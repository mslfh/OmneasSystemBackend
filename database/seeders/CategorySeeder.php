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
        // Create main categories
        $mainCategories = [
            [
                'title' => 'Main Dishes',
                'hint' => 'Hearty main courses including rice, noodles, meat and seafood',
                'status' => 'active',
                'subcategories' => ['Rice Dishes', 'Noodles', 'Meat', 'Seafood', 'Vegetarian']
            ],
            [
                'title' => 'Soups',
                'hint' => 'Nutritious and warming soups for every taste',
                'status' => 'active',
                'subcategories' => ['Clear Soup', 'Thick Soup', 'Hot Pot', 'Broth']
            ],
            [
                'title' => 'Appetizers',
                'hint' => 'Light and refreshing starters to awaken your appetite',
                'status' => 'active',
                'subcategories' => ['Cold Dishes', 'Salads', 'Finger Foods', 'Sharing Plates']
            ],
            [
                'title' => 'Desserts',
                'hint' => 'Sweet treats and delightful desserts',
                'status' => 'active',
                'subcategories' => ['Cakes', 'Ice Cream', 'Traditional Sweets', 'Puddings']
            ],
            [
                'title' => 'Beverages',
                'hint' => 'Fresh drinks and beverages to quench your thirst',
                'status' => 'active',
                'subcategories' => ['Juices', 'Tea', 'Coffee', 'Soft Drinks']
            ],
            [
                'title' => 'Specialty Items',
                'hint' => 'Unique chef specials and seasonal offerings',
                'status' => 'active',
                'subcategories' => ['Seasonal', 'Chef Special', 'Limited Edition', 'Signature']
            ]
        ];

        foreach ($mainCategories as $mainCategory) {
            // Create main category
            $category = Category::create([
                'parent_id' => null,
                'title' => $mainCategory['title'],
                'hint' => $mainCategory['hint'],
                'status' => $mainCategory['status'],
            ]);

            // Create subcategories
            foreach ($mainCategory['subcategories'] as $subcategoryTitle) {
                Category::create([
                    'parent_id' => $category->id,
                    'title' => $subcategoryTitle,
                    'hint' => 'Premium ' . $subcategoryTitle . ' with guaranteed quality',
                    'status' => 'active',
                ]);
            }
        }

        // Remove extra random categories creation since we only want 6 main categories
        // Create some additional subcategories for variety
        $parentCategories = Category::whereNull('parent_id')->pluck('id')->toArray();
        foreach ($parentCategories as $parentId) {
            if (rand(1, 4) == 1) { // 25% chance to create additional subcategory
                Category::factory(1)->subcategory($parentId)->create();
            }
        }

        $this->command->info('Created ' . Category::count() . ' categories successfully.');
        $this->command->info('Main categories: ' . Category::whereNull('parent_id')->count());
        $this->command->info('Sub categories: ' . Category::whereNotNull('parent_id')->count());
    }
}
