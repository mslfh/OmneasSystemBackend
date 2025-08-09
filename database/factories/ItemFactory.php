<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $itemTypes = [
            'meat', 'source', 'soup', 'staple', 'vegetable'
        ];

        $itemsByType = [
            'meat' => [
                'Chicken Breast', 'Beef Strips', 'Pork Belly', 'Salmon Fillet', 'Shrimp',
                'Duck Breast', 'Lamb Chops', 'Turkey Slices', 'Cod Fillet', 'Tuna Steak',
                'Crab Meat', 'Lobster Tail', 'Scallops', 'Mussels', 'Chicken Thigh'
            ],
            'source' => [
                'Soy Sauce', 'Oyster Sauce', 'Teriyaki Sauce', 'Sweet & Sour Sauce', 'Black Bean Sauce',
                'Garlic Sauce', 'Chili Sauce', 'Sesame Oil', 'Fish Sauce', 'Hoisin Sauce',
                'XO Sauce', 'Plum Sauce', 'Satay Sauce', 'Thai Curry Paste', 'Miso Paste'
            ],
            'soup' => [
                'Chicken Broth', 'Beef Bone Broth', 'Vegetable Stock', 'Miso Soup', 'Tom Yum Soup',
                'Hot & Sour Soup', 'Wonton Soup', 'Corn Soup', 'Mushroom Soup', 'Seafood Bisque',
                'Pho Broth', 'Ramen Broth', 'Clear Broth', 'Coconut Soup', 'Lentil Soup'
            ],
            'staple' => [
                'Steamed Rice', 'Fried Rice', 'Brown Rice', 'Jasmine Rice', 'Glutinous Rice',
                'Fat Noodles (Udon)', 'Fat Rice Noodles', 'Thick Wheat Noodles', 'Hand-pulled Noodles',
                'Thin Egg Noodles', 'Angel Hair Noodles', 'Ramen Noodles', 'Soba Noodles', 'Vermicelli',
                'Glass Noodles'
            ],
            'vegetable' => [
                'Bok Choy', 'Chinese Broccoli', 'Snow Peas', 'Bell Peppers', 'Mushrooms',
                'Bean Sprouts', 'Cabbage', 'Carrots', 'Onions', 'Garlic', 'Ginger',
                'Tomatoes', 'Lettuce', 'Spinach', 'Broccoli', 'Cauliflower', 'Eggplant',
                'Zucchini', 'Green Beans', 'Corn Kernels'
            ]
        ];

        $type = $this->faker->randomElement($itemTypes);
        $name = $this->faker->randomElement($itemsByType[$type]);

        $descriptions = [
            'Fresh and high-quality ingredient',
            'Premium grade item with excellent taste',
            'Locally sourced and organic when possible',
            'Carefully selected for optimal flavor',
            'Traditional preparation method',
            'Modern twist on classic ingredient',
            'Sustainably sourced product',
            'Artisanal quality item',
            'Restaurant grade ingredient',
            'Specialty imported item'
        ];

        return [
            'name' => $name,
            'type' => $type,
            'description' => $this->faker->randomElement($descriptions),
            'price' => $this->faker->randomFloat(2, 0.50, 15.00),
        ];
    }

    /**
     * Indicate that the item is free.
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => 0.00,
        ]);
    }

    /**
     * Indicate that the item is expensive.
     */
    public function expensive(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => $this->faker->randomFloat(2, 10.00, 50.00),
        ]);
    }

    /**
     * Create meat type item.
     */
    public function meat(): static
    {
        $meats = [
            'Chicken Breast', 'Beef Strips', 'Pork Belly', 'Salmon Fillet', 'Shrimp',
            'Duck Breast', 'Lamb Chops', 'Turkey Slices', 'Cod Fillet', 'Tuna Steak'
        ];

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($meats),
            'type' => 'meat',
            'price' => $this->faker->randomFloat(2, 5.00, 25.00),
        ]);
    }

    /**
     * Create source type item.
     */
    public function source(): static
    {
        $sources = [
            'Soy Sauce', 'Oyster Sauce', 'Teriyaki Sauce', 'Sweet & Sour Sauce',
            'Black Bean Sauce', 'Garlic Sauce', 'Chili Sauce', 'XO Sauce'
        ];

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($sources),
            'type' => 'source',
            'price' => $this->faker->randomFloat(2, 0.50, 3.00),
        ]);
    }

    /**
     * Create soup type item.
     */
    public function soup(): static
    {
        $soups = [
            'Chicken Broth', 'Beef Bone Broth', 'Vegetable Stock', 'Miso Soup',
            'Tom Yum Soup', 'Hot & Sour Soup', 'Wonton Soup', 'Mushroom Soup'
        ];

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($soups),
            'type' => 'soup',
            'price' => $this->faker->randomFloat(2, 2.00, 8.00),
        ]);
    }

    /**
     * Create staple type item.
     */
    public function staple(): static
    {
        $staples = [
            'Steamed Rice', 'Fried Rice', 'Fat Noodles (Udon)', 'Fat Rice Noodles',
            'Thin Egg Noodles', 'Angel Hair Noodles', 'Ramen Noodles', 'Soba Noodles'
        ];

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($staples),
            'type' => 'staple',
            'price' => $this->faker->randomFloat(2, 1.50, 6.00),
        ]);
    }

    /**
     * Create vegetable type item.
     */
    public function vegetable(): static
    {
        $vegetables = [
            'Bok Choy', 'Chinese Broccoli', 'Snow Peas', 'Bell Peppers', 'Mushrooms',
            'Bean Sprouts', 'Cabbage', 'Carrots', 'Onions', 'Broccoli'
        ];

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($vegetables),
            'type' => 'vegetable',
            'price' => $this->faker->randomFloat(2, 1.00, 4.00),
        ]);
    }
}
