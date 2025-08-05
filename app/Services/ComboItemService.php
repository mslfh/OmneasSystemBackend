<?php

namespace App\Services;

use App\Contracts\ComboItemContract;

class ComboItemService
{
    protected $comboItemRepository;

    public function __construct(ComboItemContract $comboItemRepository)
    {
        $this->comboItemRepository = $comboItemRepository;
    }

    public function getAllComboItems()
    {
        return $this->comboItemRepository->getAll();
    }

    public function getComboItemById($id)
    {
        return $this->comboItemRepository->findById($id);
    }

    public function createComboItem(array $data)
    {
        return $this->comboItemRepository->create($data);
    }

    public function updateComboItem($id, array $data)
    {
        return $this->comboItemRepository->update($id, $data);
    }

    public function deleteComboItem($id)
    {
        return $this->comboItemRepository->delete($id);
    }

    public function getByComboId($comboId)
    {
        return $this->comboItemRepository->findByComboId($comboId);
    }
}
