<?php

namespace Database\Factories;

use App\Models\Combo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Combo>
 */
class ComboFactory extends Factory
{
    protected $model = Combo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $comboNames = [
            'Classic Family Combo',
            'Business Executive Combo',
            'Romantic Dinner Combo',
            'Friends Gathering Combo',
            'Healthy Garden Combo'
        ];

        $descriptions = [
            'Classic family-style dishes that bring warmth and comfort to your dining experience',
            'Carefully curated selection for business dining, showcasing elegance and quality',
            'Romantic dining experience designed for couples, creating sweet and memorable moments',
            'Perfect choice for friends gathering, diverse flavors to satisfy different tastes',
            'Health-focused nutritious combination, balancing wellness and delicious flavors'
        ];

        $originalPrice = $this->faker->randomFloat(2, 50, 300);
        $discount = $this->faker->randomFloat(2, 5, $originalPrice * 0.4);
        $price = $originalPrice - $discount;
        $taxRate = 0.06; // 6% 税率
        $taxAmount = $price * $taxRate;

        return [
            'name' => $this->faker->randomElement($comboNames),
            'description' => $this->faker->randomElement($descriptions),
            'image' => $this->faker->imageUrl(600, 400, 'food'),
            'price' => $price,
            'original_price' => $originalPrice,
            'discount' => $discount,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'is_active' => $this->faker->boolean(85),
            'is_featured' => $this->faker->boolean(25),
        ];
    }

    /**
     * Indicate that the combo is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the combo is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the combo is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a highly discounted combo.
     */
    public function highlyDiscounted(): static
    {
        return $this->state(function (array $attributes) {
            $originalPrice = $this->faker->randomFloat(2, 80, 250);
            $discount = $this->faker->randomFloat(2, $originalPrice * 0.3, $originalPrice * 0.6);
            $price = $originalPrice - $discount;
            $taxAmount = $price * 0.06;

            return [
                'original_price' => $originalPrice,
                'discount' => $discount,
                'price' => $price,
                'tax_amount' => $taxAmount,
                'is_featured' => true,
            ];
        });
    }

    /**
     * Create a premium combo.
     */
    public function premium(): static
    {
        return $this->state(function (array $attributes) {
            $originalPrice = $this->faker->randomFloat(2, 200, 500);
            $discount = $this->faker->randomFloat(2, 10, $originalPrice * 0.2);
            $price = $originalPrice - $discount;
            $taxAmount = $price * 0.06;

            return [
                'original_price' => $originalPrice,
                'discount' => $discount,
                'price' => $price,
                'tax_amount' => $taxAmount,
            ];
        });
    }
}
