<?php

namespace App\Services;

use App\Contracts\AttributeContract;

class AttributeService
{
    protected $attributeRepository;

    public function __construct(AttributeContract $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Get all attributes
     */
    public function getAllAttributes()
    {
        return $this->attributeRepository->getAll();
    }

    /**
     * Get attribute by ID
     */
    public function getAttributeById($id)
    {
        return $this->attributeRepository->findById($id);
    }

    /**
     * Create new attribute
     */
    public function createAttribute(array $data)
    {
        return $this->attributeRepository->create($data);
    }

    /**
     * Update attribute
     */
    public function updateAttribute($id, array $data)
    {
        return $this->attributeRepository->update($id, $data);
    }

    /**
     * Delete attribute
     */
    public function deleteAttribute($id)
    {
        return $this->attributeRepository->delete($id);
    }

    /**
     * Collection of records matching field value
     */
    public function findByField(string $field, mixed $value)
    {
        return $this->attributeRepository->findByField($field, $value);
    }

    /**
     * Check if attribute exists
     */
    public function exists(int $id): bool
    {
        return $this->attributeRepository->exists($id);
    }

    /**
     * Get total count
     */
    public function count(): int
    {
        return $this->attributeRepository->count();
    }

    /**
     * Get paginated attributes
     */
    public function getPaginatedAttributes($start, $count, $filter, $sortBy, $descending, $selected)
    {
        $query = \App\Models\Attribute::query();

        if ($filter) {
            if ($filter['field'] == "name") {
                $query->where('name', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "type") {
                $query->where('type', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "extra_cost") {
                $query->where('extra_cost', '=', $filter['value']);
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
}
