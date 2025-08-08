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
            'ingredient', 'sauce', 'condiment', 'garnish', 'seasoning',
            'side_dish', 'beverage', 'addon', 'base', 'topping'
        ];

        $itemsByType = [
            'ingredient' => [
                'Chicken Breast', 'Beef Strips', 'Pork Belly', 'Salmon Fillet', 'Shrimp',
                'Mushrooms', 'Bell Peppers', 'Onions', 'Garlic', 'Ginger',
                'Tomatoes', 'Lettuce', 'Spinach', 'Broccoli', 'Carrots'
            ],
            'sauce' => [
                'Soy Sauce', 'Oyster Sauce', 'Teriyaki Sauce', 'Sweet & Sour Sauce', 'Black Bean Sauce',
                'Garlic Sauce', 'Chili Sauce', 'Sesame Oil', 'Fish Sauce', 'Hoisin Sauce'
            ],
            'condiment' => [
                'Salt', 'Black Pepper', 'White Pepper', 'Sugar', 'Vinegar',
                'Sesame Seeds', 'Chili Flakes', 'Green Onions', 'Cilantro', 'Lime'
            ],
            'garnish' => [
                'Parsley', 'Mint Leaves', 'Basil', 'Cherry Tomatoes', 'Cucumber Slices',
                'Lemon Wedges', 'Orange Zest', 'Microgreens', 'Edible Flowers', 'Pickled Vegetables'
            ],
            'seasoning' => [
                'Five Spice', 'Curry Powder', 'Paprika', 'Cumin', 'Coriander',
                'Star Anise', 'Bay Leaves', 'Thyme', 'Rosemary', 'Oregano'
            ],
            'side_dish' => [
                'Steamed Rice', 'Fried Rice', 'Noodles', 'French Fries', 'Mashed Potatoes',
                'Coleslaw', 'Salad', 'Soup', 'Bread Roll', 'Garlic Bread'
            ],
            'beverage' => [
                'Green Tea', 'Jasmine Tea', 'Coffee', 'Fresh Orange Juice', 'Apple Juice',
                'Soft Drinks', 'Sparkling Water', 'Hot Chocolate', 'Milk Tea', 'Smoothie'
            ],
            'addon' => [
                'Extra Cheese', 'Bacon Bits', 'Fried Egg', 'Avocado', 'Extra Meat',
                'Extra Vegetables', 'Nuts', 'Croutons', 'Olives', 'Pickles'
            ],
            'base' => [
                'Pizza Base', 'Burger Bun', 'Tortilla', 'Flatbread', 'Rice Paper',
                'Pasta', 'Noodle Base', 'Salad Base', 'Soup Base', 'Sauce Base'
            ],
            'topping' => [
                'Cheese', 'Pepperoni', 'Ham', 'Pineapple', 'Olives',
                'Jalapeños', 'Mushrooms', 'Onions', 'Bell Peppers', 'Anchovies'
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
     * Create ingredient type item.
     */
    public function ingredient(): static
    {
        $ingredients = [
            'Chicken Breast', 'Beef Strips', 'Pork Belly', 'Salmon Fillet', 'Shrimp',
            'Mushrooms', 'Bell Peppers', 'Onions', 'Garlic', 'Ginger'
        ];

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($ingredients),
            'type' => 'ingredient',
            'price' => $this->faker->randomFloat(2, 2.00, 12.00),
        ]);
    }

    /**
     * Create sauce type item.
     */
    public function sauce(): static
    {
        $sauces = [
            'Soy Sauce', 'Oyster Sauce', 'Teriyaki Sauce', 'Sweet & Sour Sauce',
            'Black Bean Sauce', 'Garlic Sauce', 'Chili Sauce'
        ];

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($sauces),
            'type' => 'sauce',
            'price' => $this->faker->randomFloat(2, 0.50, 3.00),
        ]);
    }

    /**
     * Create addon type item.
     */
    public function addon(): static
    {
        $addons = [
            'Extra Cheese', 'Bacon Bits', 'Fried Egg', 'Avocado', 'Extra Meat',
            'Extra Vegetables', 'Nuts', 'Croutons'
        ];

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($addons),
            'type' => 'addon',
            'price' => $this->faker->randomFloat(2, 1.00, 5.00),
        ]);
    }
}
