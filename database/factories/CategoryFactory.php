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
        // Only allow these main categories
        $mainCategories = [
            'Meal',
            'Drink',
            'Soup',
            'Snack',
        ];

        // Mapping shown for reference (not used directly in default state)
        $subCategories = [
            'Meal' => ['Noodle', 'Fry Rice'],
            'Drink' => ['200ml Tin', '600ml bottle'],
            'Soup' => [],
            'Snack' => [],
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
        // Map each subcategory to its correct parent category
        $subToParent = [
            'Noodle' => 'Meal',
            'Fry Rice' => 'Meal',
            '200ml Tin' => 'Drink',
            '600ml bottle' => 'Drink',
        ];
        $subCategories = array_keys($subToParent);

        return $this->state(function (array $attributes) use ($parentId, $subToParent, $subCategories) {
            $title = $this->faker->randomElement($subCategories);

            // Determine or create the correct parent category
            if ($parentId === null) {
                $parentTitle = $subToParent[$title];
                $parent = Category::firstOrCreate([
                    'parent_id' => null,
                    'title' => $parentTitle,
                ]);
                $resolvedParentId = $parent->id;
            } else {
                $resolvedParentId = $parentId;
            }

            return [
                'parent_id' => $resolvedParentId,
                'title' => $title,
            ];
        });
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
