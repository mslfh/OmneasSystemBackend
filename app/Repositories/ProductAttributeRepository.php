<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Contracts\ProductAttributeContract;

class ProductAttributeRepository implements ProductAttributeContract
{
    protected $model;

    public function __construct(Attribute $attribute)
    {
        $this->model = $attribute;
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
        $productAttribute = $this->findById($id);
        if ($productAttribute) {
            $productAttribute->update($data);
            return $productAttribute;
        }
        return null;
    }

    public function delete($id)
    {
        $productAttribute = $this->findById($id);
        if ($productAttribute) {
            return $productAttribute->delete();
        }
        return false;
    }

    public function findByProductId($productId)
    {
        return $this->model->where('product_id', $productId)->get();
    }
}
