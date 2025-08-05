<?php

namespace App\Contracts;

interface ComboItemContract
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findByComboId($comboId);
}
