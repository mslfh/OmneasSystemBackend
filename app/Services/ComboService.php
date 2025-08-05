<?php

namespace App\Services;

use App\Contracts\ComboContract;

class ComboService
{
    protected $comboRepository;

    public function __construct(ComboContract $comboRepository)
    {
        $this->comboRepository = $comboRepository;
    }

    public function getAllCombos()
    {
        return $this->comboRepository->findAll();
    }

    public function getComboById($id)
    {
        return $this->comboRepository->findById($id);
    }

    public function createCombo(array $data)
    {
        return $this->comboRepository->create($data);
    }

    public function updateCombo($id, array $data)
    {
        return $this->comboRepository->update($id, $data);
    }

    public function deleteCombo($id)
    {
        return $this->comboRepository->delete($id);
    }

    public function getActiveCombos()
    {
        return $this->comboRepository->getActiveItems();
    }
}
