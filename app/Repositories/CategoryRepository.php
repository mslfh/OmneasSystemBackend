<?php

namespace App\Repositories;

use App\Models\Category;
use App\Contracts\CategoryContract;

class CategoryRepository implements CategoryContract
{
    protected $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $category = $this->findById($id);
        if ($category) {
            $category->update($data);
            return $category;
        }
        return null;
    }

    public function delete($id)
    {
        $category = $this->findById($id);
        if ($category) {
            return $category->delete();
        }
        return false;
    }

    public function findByParentId($parentId)
    {
        return $this->model->where('parent_id', $parentId)->get();
    }

    public function getActiveCategories()
    {
        return $this->model->where('status', 'active')->get();
    }
}
