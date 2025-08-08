<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mainCategories = [
            'Main Dishes',
            'Soups',
            'Appetizers',
            'Desserts',
            'Beverages',
            'Specialty Items'
        ];

        $subCategories = [
            'Main Dishes' => ['Rice Dishes', 'Noodles', 'Meat', 'Seafood', 'Vegetarian'],
            'Soups' => ['Clear Soup', 'Thick Soup', 'Hot Pot', 'Broth'],
            'Appetizers' => ['Cold Dishes', 'Salads', 'Finger Foods', 'Sharing Plates'],
            'Desserts' => ['Cakes', 'Ice Cream', 'Traditional Sweets', 'Puddings'],
            'Beverages' => ['Juices', 'Tea', 'Coffee', 'Soft Drinks'],
            'Specialty Items' => ['Seasonal', 'Chef Special', 'Limited Edition', 'Signature']
        ];

        $hints = [
            'Traditional flavors perfect for everyone',
            'Innovative fusion cuisine with unique taste',
            'Healthy and nutritious ingredients sourced fresh daily',
            'Bold and spicy flavors for adventurous palates',
            'Light and refreshing, perfect for health-conscious diners',
            'Sweet and delightful, loved by all ages'
        ];

        return [
            'parent_id' => null, // 默认为主分类
            'title' => $this->faker->randomElement($mainCategories),
            'hint' => $this->faker->randomElement($hints),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }

    /**
     * Create a subcategory with a parent category.
     */
    public function subcategory(?int $parentId = null): static
    {
        $subCategories = [
            'Rice Dishes', 'Noodles', 'Meat', 'Seafood', 'Vegetarian',
            'Clear Soup', 'Thick Soup', 'Hot Pot', 'Broth',
            'Cold Dishes', 'Salads', 'Finger Foods', 'Sharing Plates',
            'Cakes', 'Ice Cream', 'Traditional Sweets', 'Puddings',
            'Juices', 'Tea', 'Coffee', 'Soft Drinks',
            'Seasonal', 'Chef Special', 'Limited Edition', 'Signature'
        ];

        return $this->state(fn (array $attributes) => [
            'parent_id' => $parentId ?? $this->faker->numberBetween(1, 6),
            'title' => $this->faker->randomElement($subCategories),
        ]);
    }

    /**
     * Indicate that the category is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
