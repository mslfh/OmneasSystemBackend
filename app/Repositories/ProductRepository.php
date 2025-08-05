<?php

namespace App\Repositories;

use App\Models\Product;
use App\Contracts\ProductContract;

class ProductRepository implements ProductContract
{
    /**
     * Get all products
     */
    public function getAll()
    {
        return Product::all();
    }

    /**
     * Find product by ID
     */
    public function findById($id)
    {
        return Product::find($id);
    }

    /**
     * Create new product
     */
    public function create(array $data)
    {
        return Product::create($data);
    }

    /**
     * Update product
     */
    public function update($id, array $data)
    {
        $product = Product::find($id);
        if ($product) {
            $product->update($data);
            return $product;
        }
        return null;
    }

    /**
     * Delete product
     */
    public function delete($id)
    {
        $product = Product::find($id);
        if ($product) {
            return $product->delete();
        }
        return false;
    }

    /**
     * Collection of products in the specified category
     */
    public function findByCategory(string $category)
    {
        // TODO: Implement findByCategory logic
        // Example: return Product::where('field', $value)->get();
        throw new \Exception('Method findByCategory not implemented yet');
    }

    /**
     * Collection of products within price range
     */
    public function findByPriceRange(float $minPrice, float $maxPrice)
    {
        // TODO: Implement findByPriceRange logic
        // Example: return Product::where('field', $value)->get();
        throw new \Exception('Method findByPriceRange not implemented yet');
    }

    /**
     * Collection of records matching field value
     */
    public function findByField(string $field, mixed $value)
    {
        return Product::where($field, $value)->get();
    }

    /**
     * bool
     */
    public function exists(int $id)
    {
        return Product::where('id', $id)->exists();
    }

    /**
     * int
     */
    public function count()
    {
        return Product::count();
    }
}
