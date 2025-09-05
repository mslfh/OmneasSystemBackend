<?php

namespace App\Repositories;

use App\Models\Product;
use App\Contracts\ProductContract;

class ProductRepository implements ProductContract
{
    protected $model;

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    /**
     * Get all products
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get all active products
     */
    public function getAllActive()
    {
        return $this->model->where('status', 'active')
        ->with('categories:id')->get();
    }

    /**
     * Find product by ID
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Create new product
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update product
     */
    public function update($id, array $data)
    {
        $product = $this->findById($id);
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
        $product = $this->findById($id);
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
        // Example: return $this->model->where('category', $category)->get();
        throw new \Exception('Method findByCategory not implemented yet');
    }

    /**
     * Collection of products within price range
     */
    public function findByPriceRange(float $minPrice, float $maxPrice)
    {
        // TODO: Implement findByPriceRange logic
        // Example: return $this->model->whereBetween('price', [$minPrice, $maxPrice])->get();
        throw new \Exception('Method findByPriceRange not implemented yet');
    }

    /**
     * Collection of records matching field value
     */
    public function findByField(string $field, mixed $value)
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * bool
     */
    public function exists(int $id)
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * int
     */
    public function count()
    {
        return $this->model->count();
    }
}
