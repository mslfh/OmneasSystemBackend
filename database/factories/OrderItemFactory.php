<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Combo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 5);

        // Create a product for this order item
        $product = Product::factory()->create();

        // Use actual product information
        $productPrice = $product->price;
        $productDiscount = $product->discount;
        $productSellingPrice = $product->selling_price;
        $finalAmount = $productSellingPrice * $quantity;

        return [
            'order_id' => Order::factory(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'is_combo' => false,
            'combo_id' => null,
            'combo_item_name' => null,
            'is_customization' => $this->faker->boolean(20), // 20% chance of customization
            'product_title' => $product->title,
            'product_second_title' => $product->second_title ?: 'Default Second Title',
            'product_items' => $this->faker->optional()->text(100),
            'product_price' => $productPrice,
            'product_discount' => $productDiscount,
            'product_selling_price' => $productSellingPrice,
            'final_amount' => $finalAmount,
        ];
    }

    /**
     * Indicate that the order item is a combo.
     */
    public function combo(): static
    {
        return $this->state(function (array $attributes) {
            $combo = Combo::factory()->create();

            return [
                'is_combo' => true,
                'combo_id' => $combo->id,
                'combo_item_name' => $combo->title ?? $this->faker->words(2, true),
            ];
        });
    }

    /**
     * Indicate that the order item has customization.
     */
    public function customized(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_customization' => true,
            'product_items' => $this->faker->text(150),
        ]);
    }

    /**
     * Set a specific quantity for the order item.
     */
    public function quantity(int $quantity): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => $quantity,
            'final_amount' => $attributes['product_selling_price'] * $quantity,
        ]);
    }

    /**
     * Set a specific product for the order item.
     */
    public function forProduct(Product $product): static
    {
        return $this->state(fn (array $attributes) => [
            'product_id' => $product->id,
            'product_title' => $product->title,
            'product_second_title' => $product->second_title ?: 'Default Second Title',
            'product_price' => $product->price,
            'product_discount' => $product->discount,
            'product_selling_price' => $product->selling_price,
            'final_amount' => $product->selling_price * $attributes['quantity'],
        ]);
    }
}
