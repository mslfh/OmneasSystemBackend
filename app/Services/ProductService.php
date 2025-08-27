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
        dd($data);


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

    /**
     * Collection of records matching field value
     */
    public function findByField(string $field, mixed $value)
    {
        return $this->productRepository->findByField($field, $value);
    }

    /**
     * bool
     */
    public function exists(int $id)
    {
        return $this->productRepository->exists($id);
    }

    /**
     * int
     */
    public function count()
    {
        return $this->productRepository->count();
    }

    /**
     * Get paginated products
     */
    public function getPaginatedProducts($start, $count, $filter, $sortBy, $descending, $selected)
    {
        $query = \App\Models\Product::query();

        if ($filter) {
            if ($filter['field'] == "title") {
                $query->where('title', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "code") {
                $query->where('code', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "description") {
                $query->where('description', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "price") {
                $query->where('price', '=', $filter['value']);
            } else if ($filter['field'] == "status") {
                $query->where('status', '=', $filter['value']);
            }
        }

        if ($selected) {
            if ($selected['field'] == "deleted") {
                $query->where('deleted_at', '!=', null);
            } else if ($selected['field'] == "featured") {
                $query->where('is_featured', true);
            } else if ($selected['field'] == "out_of_stock") {
                $query->where('stock', '<=', 0);
            }
        }

        $sortDirection = $descending ? 'desc' : 'asc';
        $query->with(['categories'])->withTrashed()->orderBy($sortBy, $sortDirection);

        $total = $query->count();
        $data = $query->skip($start)->take($count)->get();

        return [
            'data' => $data,
            'total' => $total,
        ];
    }
}
