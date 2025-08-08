<?php

namespace App\Repositories;

use App\Models\Item;
use App\Contracts\ProductItemContract;

class ProductItemRepository implements ProductItemContract
{
    protected $model;

    public function __construct(Item $Item)
    {
        $this->model = $Item;
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
        $Item = $this->findById($id);
        if ($Item) {
            $Item->update($data);
            return $Item;
        }
        return null;
    }

    public function delete($id)
    {
        $Item = $this->findById($id);
        if ($Item) {
            return $Item->delete();
        }
        return false;
    }

    public function findByProductId($productId)
    {
        return $this->model->where('product_id', $productId)->get();
    }
}
