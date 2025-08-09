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
        // Only use the four specified types and exact names
        $attributeTypes = ['EXTRA', 'CHILLI', 'NO', 'ONLY'];

        $attributesByType = [
            'EXTRA' => [
                'Extra Source',
                'Extra Prawn',
                'Extra Veg',
                'Extra Bokchoy',
                'Extra Chicken',
                'Extra Beef',
                'Extra Seafood',
                'Extra Pork',
                'Extra 3Meat',
                'Extra Tofu',
                'Extra Noodle',
                'Extra Rice',
            ],
            'CHILLI' => [
                'No Chilli',
                'Mild Chilli',
                'Extra Chilli1',
                'Extra Chilli2',
                'Extra Chilli3',
                'Extra Chilli5',
            ],
            'NO' => [
                'No Veg',
                'No Onion',
                'No Carrot',
                'No Bokchoy',
                'No Pineapple',
                'No Cabbage',
                'No Mushroom',
                'No Caps',
                'No Tofu',
                'No Garlic',
                'No Tomato',
                'No Seafood',
                'No Beef',
                'No Chicken',
                'No Pork',
                'No Egg',
                'No Noodles',
                'No Squid',
                'No FishCake',
                'No Prawn',
                'No Shrimp',
            ],
            'ONLY' => [
                'Only Chicken',
                'Only Beef',
                'Only Pork',
                'Only Seafood',
                'Only Veg',
                'Only 3Meat',
                'Only Noodles',
            ],
        ];

        $type = $this->faker->randomElement($attributeTypes);
        $name = $this->faker->randomElement($attributesByType[$type]);

        return [
            'name' => $name,
            'type' => $type,
            'extra_cost' => rand(0, 5),
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
            'extra_cost' => $this->faker->randomFloat(2, 1, 5),
        ]);
    }

    /**
     * Create size attribute.
     */
    // Remove unrelated states; provide states only for the four categories

    /**
     * Create spice level attribute.
     */
    public function chilli(): static
    {
        return $this->state(function () {
            $names = ['No Chilli', 'Mild Chilli', 'Extra Chilli1', 'Extra Chilli2', 'Extra Chilli3', 'Extra Chilli5'];
            return [
                'name' => $this->faker->randomElement($names),
                'type' => 'CHILLI',
                'extra_cost' => rand(0, 5),
            ];
        });
    }

    /**
     * Create dietary attribute.
     */
    public function no(): static
    {
        return $this->state(function () {
            $names = [
                'No Veg','No Onion','No Carrot','No Bokchoy','No Pineapple','No Cabbage','No Mushroom','No Caps','No Tofu','No Garlic','No Tomato','No Seafood','No Beef','No Chicken','No Pork','No Egg','No Noodles','No Squid','No FishCake','No Prawn','No Shrimp'
            ];
            return [
                'name' => $this->faker->randomElement($names),
                'type' => 'NO',
                'extra_cost' => rand(0, 5),
            ];
        });
    }

    /**
     * Create no spicy attribute.
     */
    public function only(): static
    {
        return $this->state(function () {
            $names = ['Only Chicken','Only Beef','Only Pork','Only Seafood','Only Veg','Only 3Meat','Only Noodles'];
            return [
                'name' => $this->faker->randomElement($names),
                'type' => 'ONLY',
                'extra_cost' => rand(0, 5),
            ];
        });
    }

    /**
     * Create no vegetables attribute.
     */
    public function extra(): static
    {
        return $this->state(function () {
            $names = ['Extra Source','Extra Prawn','Extra Veg','Extra Bokchoy','Extra Chicken','Extra Beef','Extra Seafood','Extra Pork','Extra 3Meat','Extra Tofu','Extra Noodle','Extra Rice'];
            return [
                'name' => $this->faker->randomElement($names),
                'type' => 'EXTRA',
                'extra_cost' => rand(0, 5),
            ];
        });
    }

    /**
     * Create no meats attribute.
     */
    // Removed legacy states: size, spiceLevel, dietary, noSpicy, noVeg, noMeats, sauce
}
