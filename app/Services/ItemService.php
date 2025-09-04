<?php

namespace App\Services;

use App\Contracts\ItemContract;

class ItemService
{
    protected $itemRepository;

    public function __construct(ItemContract $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Get all items
     */
    public function getAllItems()
    {
        return $this->itemRepository->getAll();
    }

    /**
     * Get item by ID
     */
    public function getItemById($id)
    {
        return $this->itemRepository->findById($id);
    }

    /**
     * Create new item
     */
    public function createItem(array $data)
    {
        return $this->itemRepository->create($data);
    }

    /**
     * Update item
     */
    public function updateItem($id, array $data)
    {
        return $this->itemRepository->update($id, $data);
    }

    /**
     * Delete item
     */
    public function deleteItem($id)
    {
        return $this->itemRepository->delete($id);
    }

    /**
     * Collection of records matching field value
     */
    public function findByField(string $field, mixed $value)
    {
        return $this->itemRepository->findByField($field, $value);
    }

    /**
     * Collection of items within price range
     */
    public function findByPriceRange(float $minPrice, float $maxPrice)
    {
        return $this->itemRepository->findByPriceRange($minPrice, $maxPrice);
    }

    /**
     * Check if item exists
     */
    public function exists(int $id): bool
    {
        return $this->itemRepository->exists($id);
    }

    /**
     * Get total count
     */
    public function count(): int
    {
        return $this->itemRepository->count();
    }

    /**
     * Get paginated items
     */
    public function getPaginatedItems($start, $count, $filter, $sortBy, $descending, $selected)
    {
        $query = \App\Models\Item::query();

        if ($filter) {
            if ($filter['field'] == "name") {
                $query->where('name', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "type") {
                $query->where('type', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "description") {
                $query->where('description', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "price") {
                $query->where('price', '=', $filter['value']);
            }
        }

        if ($selected) {
            if ($selected['field'] == "deleted") {
                $query->onlyTrashed();
            }
        }

        $sortDirection = $descending ? 'desc' : 'asc';
        $query->withTrashed()->orderBy($sortBy, $sortDirection);

        $total = $query->count();
        $data = $query->skip($start)->take($count)->get();

        return [
            'data' => $data,
            'total' => $total,
        ];
    }


    /**
     * mixed
     */
    public function getActiveItems()
    {
        return $this->itemRepository->getActiveItems();
    }

    public function getBulkItemsByIds($ids)
    {
        return $this->itemRepository->getBulkItemsByIds($ids);
    }

    public function getItemTypes()
    {
        $allItems =  $this->itemRepository->getAll();
        return $allItems->pluck('type')->unique()->values();
    }
}
