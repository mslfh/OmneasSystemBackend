<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Contracts\AttributeContract;

class AttributeRepository implements AttributeContract
{
    protected $model;

    public function __construct(Attribute $attribute)
    {
        $this->model = $attribute;
    }

    /**
     * Get all attributes
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Find attribute by ID
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Create new attribute
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update attribute
     */
    public function update($id, array $data)
    {
        $attribute = $this->findById($id);
        if ($attribute) {
            $attribute->update($data);
            return $attribute;
        }
        return null;
    }

    /**
     * Delete attribute
     */
    public function delete($id)
    {
        $attribute = $this->findById($id);
        if ($attribute) {
            return $attribute->delete();
        }
        return false;
    }

    /**
     * Find attributes by field value
     */
    public function findByField(string $field, mixed $value)
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Check if attribute exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get total count of attributes
     */
    public function count(): int
    {
        return $this->model->count();
    }
}
