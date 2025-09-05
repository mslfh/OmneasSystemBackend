<?php

namespace App\Contracts;

interface ProductContract
{
    public function getAll();
    public function getAllActive();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findByCategory(string $category);
    public function findByPriceRange(float $minPrice, float $maxPrice);
    public function findByField(string $field, mixed $value);
    public function exists(int $id);
    public function count();
}
