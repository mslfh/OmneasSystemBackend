<?php

namespace App\Services;

use App\Contracts\ProductContract;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductContract $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get all products
     */
    public function getAllProducts()
    {
        return $this->productRepository->getAll();
    }

    /**
     * Get product by ID
     */
    public function getProductById($id)
    {
        return $this->productRepository->findById($id);
    }

    /**
     * Create new product
     */
    public function createProduct(array $data)
    {
        return $this->productRepository->create($data);
    }

    /**
     * Update product
     */
    public function updateProduct($id, array $data)
    {
        return $this->productRepository->update($id, $data);
    }

    /**
     * Delete product
     */
    public function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }

    /**
     * Collection of products in the specified category
     */
    public function findByCategory(string $category)
    {
        return $this->productRepository->findByCategory($category);
    }

    /**
     * Collection of products within price range
     */
    public function findByPriceRange(float $minPrice, float $maxPrice)
    {
        return $this->productRepository->findByPriceRange($minPrice, $maxPrice);
    }
}
