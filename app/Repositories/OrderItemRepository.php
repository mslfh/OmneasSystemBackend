<?php

namespace App\Repositories;

use App\Models\OrderItem;
use App\Contracts\OrderItemContract;

class OrderItemRepository implements OrderItemContract
{
    protected $model;

    public function __construct(OrderItem $orderItem)
    {
        $this->model = $orderItem;
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
        $orderItem = $this->findById($id);
        if ($orderItem) {
            $orderItem->update($data);
            return $orderItem;
        }
        return null;
    }

    public function delete($id)
    {
        $orderItem = $this->findById($id);
        if ($orderItem) {
            return $orderItem->delete();
        }
        return false;
    }

    public function findByOrderId($orderId)
    {
        return $this->model->where('order_id', $orderId)->get();
    }
}
