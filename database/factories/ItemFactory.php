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
        $itemTypes = ['MAIN', 'VEGETABLE', 'SOURCE', 'MEAT', 'SEAFOOD'];

        $itemsByType = [
            'MAIN' => [
                'Thick Egg Noodle', 'Thin Egg Noodle', 'Thin Rice Noodle', 'Udon Noodle',
                'Flat Rice Noodle', 'Spinach Noodle', 'Rice', 'Fresh Egg Noodle(Soup Only)'
            ],
            'VEGETABLE' => [
                'Pineapple', 'Cabbage', 'Mushroom', 'Capsicum', 'Tofu', 'Tomato', 'Onion', 'Bokchoy', 'Carrot'
            ],
            'MEAT' => [
                'BBQ Pork', 'Beef', 'Chicken', 'Egg'
            ],
            'SEAFOOD' => [
                'Prawn', 'Shrimp', 'FishCake', 'Squid', 'SeafoodEXT'
            ],
            'SOURCE' => [
                'Asia Source', 'Yaki Source', 'Satay Source', 'Gluten Free( Singapore)', 'HotBox Source',
                'HokkieMEE Source', 'Sambal Source', 'MaMaMEE Source', 'SweetBox Source', 'BlackBean Source',
                'Garlic Source', 'SweetHoney Source', 'Oyster Source'
            ]
        ];

        $type = $this->faker->randomElement($itemTypes);
        $name = $this->faker->randomElement($itemsByType[$type]);

        return [
            'name' => $name,
            'type' => $type,
            'description' => 'Premium quality ingredient',
            'price' => $this->faker->randomFloat(2, 1.00, 8.00),
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
     * Create main type item.
     */
    public function main(): static
    {
        $items = [
            'Thick Egg Noodle', 'Thin Egg Noodle', 'Thin Rice Noodle', 'Udon Noodle',
            'Flat Rice Noodle', 'Spinach Noodle', 'Rice', 'Fresh Egg Noodle(Soup Only)'
        ];

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($items),
            'type' => 'MAIN',
            'price' => $this->faker->randomFloat(2, 3.00, 8.00),
        ]);
    }

    /**
     * Create vegetable type item.
     */
    public function vegetable(): static
    {
        $items = [
            'Pineapple', 'Cabbage', 'Mushroom', 'Capsicum', 'Tofu', 'Tomato', 'Onion', 'Bokchoy', 'Carrot'
        ];

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($items),
            'type' => 'VEGETABLE',
            'price' => $this->faker->randomFloat(2, 1.00, 4.00),
        ]);
    }

    /**
     * Create meat type item.
     */
    public function meat(): static
    {
        $items = ['BBQ Pork', 'Beef', 'Chicken', 'Egg'];

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($items),
            'type' => 'MEAT',
            'price' => $this->faker->randomFloat(2, 5.00, 12.00),
        ]);
    }

    /**
     * Create seafood type item.
     */
    public function seafood(): static
    {
        $items = ['Prawn', 'Shrimp', 'FishCake', 'Squid', 'SeafoodEXT'];

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($items),
            'type' => 'SEAFOOD',
            'price' => $this->faker->randomFloat(2, 6.00, 15.00),
        ]);
    }

    /**
     * Create source type item.
     */
    public function source(): static
    {
        $items = [
            'Asia Source', 'Yaki Source', 'Satay Source', 'Gluten Free( Singapore)', 'HotBox Source',
            'HokkieMEE Source', 'Sambal Source', 'MaMaMEE Source', 'SweetBox Source', 'BlackBean Source',
            'Garlic Source', 'SweetHoney Source', 'Oyster Source'
        ];

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($items),
            'type' => 'SOURCE',
            'price' => $this->faker->randomFloat(2, 0.50, 3.00),
        ]);
    }
}
