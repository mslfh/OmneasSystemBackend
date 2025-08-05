<?php

namespace App\Services;

use App\Contracts\ProductProfileContract;
use Illuminate\Http\JsonResponse;

class ProductProfileService
{
    protected $productProfileRepository;

    public function __construct(ProductProfileContract $productProfileRepository)
    {
        $this->productProfileRepository = $productProfileRepository;
    }

    public function getAll()
    {
        return $this->productProfileRepository->getAll();
    }

    public function findById($id)
    {
        return $this->productProfileRepository->findById($id);
    }

    public function create(array $data)
    {
        return $this->productProfileRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->productProfileRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->productProfileRepository->delete($id);
    }
}
