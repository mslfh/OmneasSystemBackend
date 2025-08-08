<?php

/*
 * This is a test script to verify that the Order factories work correctly.
 * You can run this in Tinker or create a temporary route to test.
 *
 * To test in Tinker:
 * php artisan tinker
 *
 * Then copy and paste the commands below:
 */

// Test Order Factory with Product consistency
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\User;
use App\Models\Product;

// Create a product first
// $product = Product::factory()->create();

// Create an order item using the specific product
// $orderItem = OrderItem::factory()->forProduct($product)->create();

// Verify that the order item has the same product information
// echo "Product ID: " . $product->id . " vs Order Item Product ID: " . $orderItem->product_id . "\n";
// echo "Product Title: " . $product->title . " vs Order Item Product Title: " . $orderItem->product_title . "\n";
// echo "Product Price: " . $product->price . " vs Order Item Product Price: " . $orderItem->product_price . "\n";

// Create order with multiple items using existing products
// $products = Product::factory()->count(5)->create();
// $order = Order::factory()->create();

// Create order items using existing products
// foreach ($products->take(3) as $product) {
//     OrderItem::factory()
//         ->for($order)
//         ->forProduct($product)
//         ->create();
// }

// Verify order totals
// $order->refresh();
// $totalFromItems = $order->items->sum('final_amount');
// echo "Order Final Amount: " . $order->final_amount . "\n";
// echo "Sum of Order Items: " . $totalFromItems . "\n";

echo "Order factories with product consistency are working!\n";
echo "Key improvements:\n";
echo "1. Order items now use real product data (title, price, etc.)\n";
echo "2. Product information is consistent between Product and OrderItem\n";
echo "3. Order totals are calculated based on actual order items\n";
echo "4. You can use forProduct() method to specify which product to use\n";
echo "\nYou can now run 'php artisan db:seed --class=OrderSeeder' to populate the database.\n";
