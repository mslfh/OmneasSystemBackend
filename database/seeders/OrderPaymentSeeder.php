<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderPayment;

class OrderPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This seeder is mainly used independently when you need to create order payments
        // without creating full orders. Most of the time, order payments are created
        // along with orders in the OrderSeeder.

        // Create completed payments
        OrderPayment::factory()->count(20)->completed()->create();

        // Create pending payments
        OrderPayment::factory()->count(5)->pending()->create();

        // Create failed payments
        OrderPayment::factory()->count(3)->failed()->create();

        // Create refunded payments
        OrderPayment::factory()->count(2)->refunded()->create();

        // Create payments with different payment methods
        OrderPayment::factory()->count(5)->cash()->completed()->create();
        OrderPayment::factory()->count(5)->card()->completed()->create();
        OrderPayment::factory()->count(5)->digitalWallet()->completed()->create();
    }
}
