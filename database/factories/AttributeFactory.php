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
            'size', 'color', 'material', 'texture', 'temperature', 'spice_level',
            'cooking_method', 'dietary', 'portion', 'sweetness'
        ];

        $attributesByType = [
            'size' => ['Small', 'Medium', 'Large', 'Extra Large', 'Mini'],
            'color' => ['Red', 'Blue', 'Green', 'Yellow', 'Black', 'White', 'Orange', 'Purple'],
            'material' => ['Cotton', 'Silk', 'Leather', 'Plastic', 'Metal', 'Wood', 'Glass'],
            'texture' => ['Smooth', 'Rough', 'Soft', 'Hard', 'Crispy', 'Creamy', 'Chewy'],
            'temperature' => ['Hot', 'Cold', 'Warm', 'Frozen', 'Room Temperature'],
            'spice_level' => ['Mild', 'Medium', 'Spicy', 'Extra Spicy', 'No Spice'],
            'cooking_method' => ['Grilled', 'Fried', 'Steamed', 'Boiled', 'Roasted', 'Baked'],
            'dietary' => ['Vegetarian', 'Vegan', 'Gluten-Free', 'Sugar-Free', 'Low-Fat', 'High-Protein'],
            'portion' => ['Single', 'Double', 'Family Size', 'Sharing', 'Individual'],
            'sweetness' => ['No Sugar', 'Low Sugar', 'Medium Sweet', 'Sweet', 'Extra Sweet']
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
        return $this->state(fn (array $attributes) => [
            'extra_cost' => 0.00,
        ]);
    }

    /**
     * Indicate that the attribute has extra cost.
     */
    public function withCost(): static
    {
        return $this->state(fn (array $attributes) => [
            'extra_cost' => $this->faker->randomFloat(2, 1, 25),
        ]);
    }

    /**
     * Create size attribute.
     */
    public function size(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement(['Small', 'Medium', 'Large', 'Extra Large']),
            'type' => 'size',
        ]);
    }

    /**
     * Create spice level attribute.
     */
    public function spiceLevel(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement(['Mild', 'Medium', 'Spicy', 'Extra Spicy']),
            'type' => 'spice_level',
        ]);
    }

    /**
     * Create dietary attribute.
     */
    public function dietary(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement(['Vegetarian', 'Vegan', 'Gluten-Free', 'Sugar-Free']),
            'type' => 'dietary',
            'extra_cost' => 0.00,
        ]);
    }
}
