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
        // Define the exact category structure
        $categories = [
            'Meal' => [
                'hint' => 'Traditional flavors perfect for everyone',
                'subcategories' => [
                    'Noodle' => 'Delicious noodle dishes with rich flavors',
                    'Fried Rice' => 'Perfectly seasoned fried rice variations'
                ]
            ],
            'Drink' => [
                'hint' => 'Light and refreshing, perfect for health-conscious diners',
                'subcategories' => [
                    '320ml Tin' => 'Convenient single-serving beverages',
                    '600ml bottle' => 'Large sharing size beverages'
                ]
            ],
            'Soup' => [
                'hint' => 'Healthy and nutritious ingredients sourced fresh daily',
                'subcategories' => []
            ],
            'Snack' => [
                'hint' => 'Sweet and delightful, loved by all ages',
                'subcategories' => []
            ]
        ];

        foreach ($categories as $title => $data) {
            // Create main category
            $mainCategory = Category::updateOrCreate(
                ['parent_id' => null, 'title' => $title],
                [
                    'hint' => $data['hint'],
                    'status' => 'active'
                ]
            );

            // Create subcategories if any
            foreach ($data['subcategories'] as $subTitle => $subHint) {
                Category::updateOrCreate(
                    ['parent_id' => $mainCategory->id, 'title' => $subTitle],
                    [
                        'hint' => $subHint,
                        'status' => 'active'
                    ]
                );
            }
        }

        if (property_exists($this, 'command') && $this->command) {
            $this->command->info('Seeded Categories: ' . Category::count());
            $this->command->info('Main categories: ' . Category::whereNull('parent_id')->count());
            $this->command->info('Sub categories: ' . Category::whereNotNull('parent_id')->count());
        }
    }
}
