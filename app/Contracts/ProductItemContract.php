<?php

namespace App\Contracts;

interface ProductItemContract
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findByProductId($productId);
}
