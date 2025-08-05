<?php

namespace App\Services;

use App\Contracts\CategoryContract;
use App\Repositories\CategoryRepository;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function findAll()
    {
        return $this->categoryRepository->findAll();
    }

    public function findById($id)
    {
        return $this->categoryRepository->findById($id);
    }

    public function create(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->categoryRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->categoryRepository->delete($id);
    }

    public function findByParentId($parentId)
    {
        return $this->categoryRepository->findByParentId($parentId);
    }

    public function getActiveCategories()
    {
        return $this->categoryRepository->getActiveCategories();
    }
}
