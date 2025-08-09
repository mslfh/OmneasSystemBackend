<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users first if they don't exist
        if (User::count() === 0) {
            User::factory()->count(10)->create();
        }

        // Use existing products (created by ProductSeeder)
        $users = User::all();
        $products = Product::all();

        if ($products->count() === 0) {
            $this->command->warn('No products found. Please run ProductSeeder first.');
            return;
        }

        // Create completed orders
        Order::factory()
            ->count(15)
            ->completed()
            ->create()
            ->each(function ($order) use ($products) {
                // Create 1-4 order items for each order
                $itemCount = rand(1, 4);
                $totalAmount = 0;

                for ($i = 0; $i < $itemCount; $i++) {
                    $orderItem = OrderItem::factory()
                        ->for($order)
                        ->forProduct($products->random())
                        ->create();
                    $totalAmount += $orderItem->final_amount;
                }

                // Update order totals based on actual order items
                $taxAmount = $totalAmount * $order->tax_rate;
                $finalAmount = $totalAmount + $taxAmount - $order->discount_amount;

                $order->update([
                    'total_amount' => $totalAmount,
                    'tax_amount' => $taxAmount,
                    'final_amount' => $finalAmount,
                    'paid_amount' => $finalAmount, // Completed orders are fully paid
                ]);

                // Create payment for completed orders
                OrderPayment::factory()
                    ->for($order)
                    ->completed()
                    ->create([
                        'amount' => $order->final_amount,
                    ]);
            });

        // Create pending orders
        Order::factory()
            ->count(8)
            ->pending()
            ->create()
            ->each(function ($order) use ($products) {
                // Create 1-3 order items for each order
                $itemCount = rand(1, 3);
                $totalAmount = 0;

                for ($i = 0; $i < $itemCount; $i++) {
                    $orderItem = OrderItem::factory()
                        ->for($order)
                        ->forProduct($products->random())
                        ->create();
                    $totalAmount += $orderItem->final_amount;
                }

                // Update order totals based on actual order items
                $taxAmount = $totalAmount * $order->tax_rate;
                $finalAmount = $totalAmount + $taxAmount - $order->discount_amount;

                $order->update([
                    'total_amount' => $totalAmount,
                    'tax_amount' => $taxAmount,
                    'final_amount' => $finalAmount,
                ]);

                // Some pending orders might have partial payments
                if (rand(0, 100) < 30) { // 30% chance
                    OrderPayment::factory()
                        ->for($order)
                        ->pending()
                        ->create([
                            'amount' => $order->final_amount * 0.5, // 50% payment
                        ]);
                }
            });

        // Create cancelled orders
        Order::factory()
            ->count(3)
            ->cancelled()
            ->create()
            ->each(function ($order) use ($products) {
                // Create 1-2 order items for cancelled orders
                $itemCount = rand(1, 2);
                for ($i = 0; $i < $itemCount; $i++) {
                    OrderItem::factory()
                        ->for($order)
                        ->forProduct($products->random())
                        ->create();
                }
            });

        // Create some orders with customized items
        Order::factory()
            ->count(5)
            ->create()
            ->each(function ($order) use ($products) {
                // Create customized order items
                OrderItem::factory()
                    ->count(2)
                    ->for($order)
                    ->forProduct($products->random())
                    ->customized()
                    ->create();

                // Create payment
                OrderPayment::factory()
                    ->for($order)
                    ->completed()
                    ->create([
                        'amount' => $order->final_amount,
                    ]);
            });

        // Create orders for different types (dine-in, takeaway, delivery)
        Order::factory()->count(5)->dineIn()->completed()->create()
            ->each(function ($order) use ($products) {
                OrderItem::factory()
                    ->count(rand(2, 5))
                    ->for($order)
                    ->forProduct($products->random())
                    ->create();

                OrderPayment::factory()
                    ->for($order)
                    ->completed()
                    ->cash()
                    ->create(['amount' => $order->final_amount]);
            });

        Order::factory()->count(5)->takeaway()->completed()->create()
            ->each(function ($order) use ($products) {
                OrderItem::factory()
                    ->count(rand(1, 3))
                    ->for($order)
                    ->forProduct($products->random())
                    ->create();

                OrderPayment::factory()
                    ->for($order)
                    ->completed()
                    ->card()
                    ->create(['amount' => $order->final_amount]);
            });

        Order::factory()->count(5)->delivery()->completed()->create()
            ->each(function ($order) use ($products) {
                OrderItem::factory()
                    ->count(rand(1, 4))
                    ->for($order)
                    ->forProduct($products->random())
                    ->create();

                OrderPayment::factory()
                    ->for($order)
                    ->completed()
                    ->digitalWallet()
                    ->create(['amount' => $order->final_amount]);
            });
    }

    /**
     * Create order items and update order totals
     */
    private function createOrderItemsAndUpdateTotals($order, $products, $itemCount, $isCompleted = true)
    {
        $totalAmount = 0;

        for ($i = 0; $i < $itemCount; $i++) {
            $orderItem = OrderItem::factory()
                ->for($order)
                ->forProduct($products->random())
                ->create();
            $totalAmount += $orderItem->final_amount;
        }

        // Update order totals based on actual order items
        $taxAmount = $totalAmount * $order->tax_rate;
        $finalAmount = $totalAmount + $taxAmount - $order->discount_amount;

        $updateData = [
            'total_amount' => $totalAmount,
            'tax_amount' => $taxAmount,
            'final_amount' => $finalAmount,
        ];

        if ($isCompleted) {
            $updateData['paid_amount'] = $finalAmount;
        }

        $order->update($updateData);

        return $order;
    }
}
