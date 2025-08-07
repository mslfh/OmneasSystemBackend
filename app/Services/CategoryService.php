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

    public function getAll()
    {
        return $this->categoryRepository->getAll();
    }

    public function getAllCategories()
    {
        return $this->getAll();
    }

    public function getCategoryById($id)
    {
        return $this->categoryRepository->findById($id);
    }

    public function findById($id)
    {
        return $this->getCategoryById($id);
    }

    public function createCategory(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    public function create(array $data)
    {
        return $this->createCategory($data);
    }

    public function updateCategory($id, array $data)
    {
        return $this->categoryRepository->update($id, $data);
    }

    public function update($id, array $data)
    {
        return $this->updateCategory($id, $data);
    }

    public function deleteCategory($id)
    {
        return $this->categoryRepository->delete($id);
    }

    public function delete($id)
    {
        return $this->deleteCategory($id);
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
