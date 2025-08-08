<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalAmount = $this->faker->randomFloat(2, 10, 500);
        $taxRate = $this->faker->randomFloat(2, 0, 0.15); // 0% to 15% tax
        $taxAmount = $totalAmount * $taxRate;
        $discountAmount = $this->faker->randomFloat(2, 0, $totalAmount * 0.2); // 0% to 20% discount
        $finalAmount = $totalAmount + $taxAmount - $discountAmount;
        $paidAmount = $this->faker->boolean(80) ? $finalAmount : $this->faker->randomFloat(2, 0, $finalAmount);

        return [
            'order_number' => 'ORD-' . $this->faker->unique()->numberBetween(100000, 999999),
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['dine_in', 'takeaway', 'delivery']),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled']),
            'total_amount' => $totalAmount,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'paid_amount' => $paidAmount,
            'payment_method' => $this->faker->randomElement(['cash', 'card', 'digital_wallet', 'bank_transfer']),
            'tag' => $this->faker->optional()->randomElement(['urgent', 'vip', 'special']),
            'note' => $this->faker->optional()->sentence(),
            'remark' => $this->faker->optional()->text(100),
        ];
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the order is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'paid_amount' => $attributes['final_amount'],
        ]);
    }

    /**
     * Indicate that the order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Indicate that the order is for dine-in.
     */
    public function dineIn(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'dine_in',
        ]);
    }

    /**
     * Indicate that the order is for takeaway.
     */
    public function takeaway(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'takeaway',
        ]);
    }

    /**
     * Indicate that the order is for delivery.
     */
    public function delivery(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'delivery',
        ]);
    }
}
