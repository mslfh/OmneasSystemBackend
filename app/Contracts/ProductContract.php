<?php

namespace App\Contracts;

interface ProductContract
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findByCategory(string $category);
    public function findByPriceRange(float $minPrice, float $maxPrice);
}
