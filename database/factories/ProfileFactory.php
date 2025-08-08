<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $restaurantProfiles = [
            'Business Lunch Profile',
            'Family Dinner Profile',
            'Quick Service Profile'
        ];

        $descriptions = [
            'Professional dining configuration designed for business meetings and corporate events',
            'Family-friendly meal setup perfect for group dining and celebrations',
            'Fast and efficient service profile for quick meals and takeaway orders'
        ];

        $settings = [
            'max_items' => $this->faker->numberBetween(3, 10),
            'discount_rate' => $this->faker->randomFloat(2, 0.05, 0.30),
            'allow_customization' => $this->faker->boolean(70),
            'priority_level' => $this->faker->numberBetween(1, 5),
            'time_slots' => $this->faker->randomElements(['morning', 'afternoon', 'evening', 'night'], $this->faker->numberBetween(1, 3)),
            'cuisine_type' => $this->faker->randomElement(['Chinese', 'Western', 'Japanese', 'Korean', 'Thai', 'Italian']),
        ];

        return [
            'name' => $this->faker->randomElement($restaurantProfiles),
            'description' => $this->faker->randomElement($descriptions),
            'status' => $this->faker->randomElement(['active', 'inactive', 'draft']),
            'settings' => $settings,
        ];
    }

    /**
     * Indicate that the profile is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the profile is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
