<?php

namespace App\Services;

use App\Contracts\ProductItemContract;
use Illuminate\Http\JsonResponse;

class ProductItemService
{
    protected $productItemRepository;

    public function __construct(ProductItemContract $productItemRepository)
    {
        $this->productItemRepository = $productItemRepository;
    }

    public function getAll()
    {
        return $this->productItemRepository->getAll();
    }

    public function findById($id)
    {
        return $this->productItemRepository->findById($id);
    }

    public function create(array $data)
    {
        return $this->productItemRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->productItemRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->productItemRepository->delete($id);
    }

    public function findByProductId($productId)
    {
        return $this->productItemRepository->findByProductId($productId);
    }
}
