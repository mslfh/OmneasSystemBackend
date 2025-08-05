<?php

namespace App\Services;

use App\Contracts\ComboProductContract;

class ComboProductService
{
    protected $comboProductRepository;

    public function __construct(ComboProductContract $comboProductRepository)
    {
        $this->comboProductRepository = $comboProductRepository;
    }

    public function getAllComboProducts()
    {
        return $this->comboProductRepository->getAll();
    }

    public function getComboProductById($id)
    {
        return $this->comboProductRepository->findById($id);
    }

    public function createComboProduct(array $data)
    {
        return $this->comboProductRepository->create($data);
    }

    public function updateComboProduct($id, array $data)
    {
        return $this->comboProductRepository->update($id, $data);
    }

    public function deleteComboProduct($id)
    {
        return $this->comboProductRepository->delete($id);
    }

    public function getByComboId($comboId)
    {
        return $this->comboProductRepository->findByComboId($comboId);
    }
}
