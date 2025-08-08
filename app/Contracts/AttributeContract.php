<?php

namespace App\Contracts;

interface AttributeContract
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findByField(string $field, mixed $value);
    public function exists(int $id);
    public function count();
}
