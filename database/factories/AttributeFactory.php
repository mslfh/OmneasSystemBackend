<?php

namespace Database\Factories;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attribute>
 */
class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $attributeTypes = [
            'spice_level',
            'no_spicy',
            'no_veg',
            'no_meats',
            'only',
            'chili',
            'extra',
            'sauce'
        ];

        $attributesByType = [
            'spice_level' => ['chilli 3', 'chilli 5', 'chilli 7', 'chilli 10'],
            'no_spicy' => ['No Spicy', 'No Hot', 'No Chili', 'Mild Only'],
            'no_veg' => ['No Vegetables', 'No Greens', 'No Onions', 'No Herbs'],
            'no_meats' => ['No Meat', 'No Beef', 'No Pork', 'No Chicken', 'No Seafood'],
            'only' => ['Cheese Only', 'Sauce Only', 'Bread Only', 'Rice Only', 'Noodles Only'],
            'chili' => ['Green Chili', 'Red Chili', 'Bird Eye Chili', 'Bell Pepper', 'Jalapeno'],
            'extra' => ['Extra Cheese', 'Extra Sauce', 'Extra Meat', 'Extra Vegetables', 'Extra Portion'],
            'sauce' => ['Tomato Sauce', 'BBQ Sauce', 'Mayo', 'Chili Sauce', 'Garlic Sauce', 'Sweet & Sour']
        ];

        $type = $this->faker->randomElement($attributeTypes);
        $name = $this->faker->randomElement($attributesByType[$type]);

        return [
            'name' => $name,
            'type' => $type,
            'extra_cost' => $this->faker->boolean(40) ? $this->faker->randomFloat(2, 0, 50) : 0.00,
        ];
    }

    /**
     * Indicate that the attribute has no extra cost.
     */
    public function free(): static
    {
        return $this->state(fn(array $attributes) => [
            'extra_cost' => 0.00,
        ]);
    }

    /**
     * Indicate that the attribute has extra cost.
     */
    public function withCost(): static
    {
        return $this->state(fn(array $attributes) => [
            'extra_cost' => $this->faker->randomFloat(2, 1, 25),
        ]);
    }

    /**
     * Create size attribute.
     */
    public function size(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => $this->faker->randomElement(['Small', 'Medium', 'Large', 'Extra Large']),
            'type' => 'size',
        ]);
    }

    /**
     * Create spice level attribute.
     */
    public function spiceLevel(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => $this->faker->randomElement(['chilli 3', 'chilli 5', 'chilli 7', 'chilli 10']),
            'type' => 'spice_level',
        ]);
    }

    /**
     * Create dietary attribute.
     */
    public function dietary(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => $this->faker->randomElement(['Vegetarian', 'Vegan', 'Gluten-Free', 'Sugar-Free']),
            'type' => 'dietary',
            'extra_cost' => 0.00,
        ]);
    }

    /**
     * Create no spicy attribute.
     */
    public function noSpicy(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => $this->faker->randomElement(['No Spicy', 'No Hot', 'No Chili', 'Mild Only']),
            'type' => 'no_spicy',
            'extra_cost' => 0.00,
        ]);
    }

    /**
     * Create no vegetables attribute.
     */
    public function noVeg(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => $this->faker->randomElement(['No Vegetables', 'No Greens', 'No Onions', 'No Herbs']),
            'type' => 'no_veg',
            'extra_cost' => 0.00,
        ]);
    }

    /**
     * Create no meats attribute.
     */
    public function noMeats(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => $this->faker->randomElement(['No Meat', 'No Beef', 'No Pork', 'No Chicken', 'No Seafood']),
            'type' => 'no_meats',
            'extra_cost' => 0.00,
        ]);
    }

    /**
     * Create only attribute.
     */
    public function only(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => $this->faker->randomElement(['Cheese Only', 'Sauce Only', 'Bread Only', 'Rice Only', 'Noodles Only']),
            'type' => 'only',
            'extra_cost' => $this->faker->randomFloat(2, 0, 10),
        ]);
    }

    /**
     * Create chili attribute.
     */
    public function chili(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => $this->faker->randomElement(['Green Chili', 'Red Chili', 'Bird Eye Chili', 'Bell Pepper', 'Jalapeno']),
            'type' => 'chili',
            'extra_cost' => $this->faker->randomFloat(2, 0, 5),
        ]);
    }

    /**
     * Create extra attribute.
     */
    public function extra(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => $this->faker->randomElement(['Extra Cheese', 'Extra Sauce', 'Extra Meat', 'Extra Vegetables', 'Extra Portion']),
            'type' => 'extra',
            'extra_cost' => $this->faker->randomFloat(2, 2, 15),
        ]);
    }

    /**
     * Create sauce attribute.
     */
    public function sauce(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => $this->faker->randomElement(['Tomato Sauce', 'BBQ Sauce', 'Mayo', 'Chili Sauce', 'Garlic Sauce', 'Sweet & Sour']),
            'type' => 'sauce',
            'extra_cost' => $this->faker->randomFloat(2, 0, 8),
        ]);
    }
}
