<?php

namespace App\Repositories;

use App\Models\ProductItem;
use App\Contracts\ProductItemContract;

class ProductItemRepository implements ProductItemContract
{
    protected $model;

    public function __construct(ProductItem $productItem)
    {
        $this->model = $productItem;
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
        $productItem = $this->findById($id);
        if ($productItem) {
            $productItem->update($data);
            return $productItem;
        }
        return null;
    }

    public function delete($id)
    {
        $productItem = $this->findById($id);
        if ($productItem) {
            return $productItem->delete();
        }
        return false;
    }

    public function findByProductId($productId)
    {
        return $this->model->where('product_id', $productId)->get();
    }
}
