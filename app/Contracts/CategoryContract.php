<?php

namespace App\Contracts;

interface CategoryContract
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findByParentId($parentId);
    public function getActiveCategories();
}
