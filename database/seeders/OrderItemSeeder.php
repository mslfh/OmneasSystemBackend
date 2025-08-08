<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This seeder is mainly used independently when you need to create order items
        // without creating full orders. Most of the time, order items are created
        // along with orders in the OrderSeeder.

        OrderItem::factory()->count(50)->create();

        // Create some combo order items
        OrderItem::factory()->count(10)->combo()->create();

        // Create some customized order items
        OrderItem::factory()->count(15)->customized()->create();

        // Create order items with specific quantities
        OrderItem::factory()->count(5)->quantity(1)->create();
        OrderItem::factory()->count(5)->quantity(2)->create();
        OrderItem::factory()->count(5)->quantity(3)->create();
    }
}
