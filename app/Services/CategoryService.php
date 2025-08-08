<?php

namespace App\Services;

use App\Contracts\CategoryContract;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryContract $categoryRepository)
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

    /**
     * Get paginated categories
     */
    public function getPaginatedCategories($start, $count, $filter, $sortBy, $descending, $selected)
    {
        $query = \App\Models\Category::query();

        if ($filter) {
            if ($filter['field'] == "title") {
                $query->where('title', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "hint") {
                $query->where('hint', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "status") {
                $query->where('status', '=', $filter['value']);
            }
        }

        if ($selected) {
            if ($selected['field'] == "deleted") {
                $query->where('deleted_at', '!=', null);
            } else if ($selected['field'] == "parent_only") {
                $query->whereNull('parent_id');
            } else if ($selected['field'] == "child_only") {
                $query->whereNotNull('parent_id');
            }
        }

        $sortDirection = $descending ? 'desc' : 'asc';
        $query->with(['parent', 'children', 'products'])->withTrashed()->orderBy($sortBy, $sortDirection);

        $total = $query->count();
        $data = $query->skip($start)->take($count)->get();

        return [
            'data' => $data,
            'total' => $total,
        ];
    }

    /**
     * Collection of records matching field value
     */
    public function findByField(string $field, mixed $value)
    {
        return $this->categoryRepository->findByField($field, $value);
    }

    /**
     * Check if category exists
     */
    public function exists(int $id): bool
    {
        return $this->categoryRepository->exists($id);
    }

    /**
     * Get total count
     */
    public function count(): int
    {
        return $this->categoryRepository->count();
    }
}
