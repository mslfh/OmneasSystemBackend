<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create exactly 25 products total
        // Create 20 basic active products
        Product::factory(20)->active()->create();

        // Create 3 featured products
        Product::factory(3)->featured()->create();

        // Create 2 discounted products
        Product::factory(2)->discounted()->create();

        // Remove signature dishes section since we only want 25 products from factory

        // Assign categories to products
        $this->assignCategoriesToProducts();

        // Assign profiles to products
        $this->assignProfilesToProducts();

        $this->command->info('Created ' . Product::count() . ' products successfully.');
    }

    /**
     * Assign categories to products
     */
    private function assignCategoriesToProducts(): void
    {
        $products = Product::all();
        $categories = Category::whereNotNull('parent_id')->get(); // Only use subcategories

        foreach ($products as $product) {
            // Each product gets randomly assigned 1-3 categories
            $randomCategories = $categories->random(rand(1, min(3, $categories->count())));
            $product->categories()->attach($randomCategories->pluck('id')->toArray());
        }

        $this->command->info('Assigned categories to products.');
    }

    /**
     * Assign profiles to products
     */
    private function assignProfilesToProducts(): void
    {
        $products = Product::all();
        $profiles = Profile::where('status', 'active')->get();

        if ($profiles->count() > 0) {
            foreach ($products as $product) {
                // 30% of products will be assigned to profiles
                if (rand(1, 100) <= 30) {
                    $randomProfiles = $profiles->random(rand(1, min(2, $profiles->count())));
                    $product->profiles()->attach($randomProfiles->pluck('id')->toArray());
                }
            }

            $this->command->info('Assigned profiles to products.');
        }
    }
}
