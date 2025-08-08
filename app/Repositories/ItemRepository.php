<?php

namespace App\Repositories;

use App\Models\Item;
use App\Contracts\ItemContract;

class ItemRepository implements ItemContract
{
    protected $model;

    public function __construct(Item $item)
    {
        $this->model = $item;
    }

    /**
     * Get all items
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Find item by ID
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Create new item
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update item
     */
    public function update($id, array $data)
    {
        $item = $this->findById($id);
        if ($item) {
            $item->update($data);
            return $item;
        }
        return null;
    }

    /**
     * Delete item
     */
    public function delete($id)
    {
        $item = $this->findById($id);
        if ($item) {
            return $item->delete();
        }
        return false;
    }

    /**
     * Find items by field value
     */
    public function findByField(string $field, mixed $value)
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Find items within price range
     */
    public function findByPriceRange(float $minPrice, float $maxPrice)
    {
        return $this->model->whereBetween('price', [$minPrice, $maxPrice])->get();
    }

    /**
     * Check if item exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get total count of items
     */
    public function count(): int
    {
        return $this->model->count();
    }
}
