<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            StaffSeeder::class,
            ProfileSeeder::class,
            CategorySeeder::class,
            AttributeSeeder::class,
            ItemSeeder::class,
            ProductSeeder::class,
            ComboSeeder::class,
            // Note: OrderItemSeeder and OrderPaymentSeeder are included in OrderSeeder
            // Uncomment below if you want to run them separately
            // OrderItemSeeder::class,
            // OrderPaymentSeeder::class,
        ]);
    }
}
