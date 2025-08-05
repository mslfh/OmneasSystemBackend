<?php

namespace App\Services;

use App\Contracts\ProductAttributeContract;
use Illuminate\Http\JsonResponse;

class ProductAttributeService
{
    protected $productAttributeRepository;

    public function __construct(ProductAttributeContract $productAttributeRepository)
    {
        $this->productAttributeRepository = $productAttributeRepository;
    }

    public function getAll()
    {
        return $this->productAttributeRepository->getAll();
    }

    public function findById($id)
    {
        return $this->productAttributeRepository->findById($id);
    }

    public function create(array $data)
    {
        return $this->productAttributeRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->productAttributeRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->productAttributeRepository->delete($id);
    }

    public function findByProductId($productId)
    {
        return $this->productAttributeRepository->findByProductId($productId);
    }
}
