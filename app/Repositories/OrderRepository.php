<?php

namespace App\Repositories;

use App\Contracts\OrderContract;
use App\Models\Order;

class OrderRepository implements OrderContract
{
    protected $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    // Standard CRUD methods
    public function getAll()
    {
        return $this->model->with('payment')->get();
    }

    public function findById($id)
    {
        return $this->model->where('id', $id)->with('payments')->first();
    }

    public function create(array $data)
    {
        $order = $this->model->create($data);
        if(isset($data['payment'])) {
            foreach ($data['payment'] as $key => $value) {
                $data['payment'][$key]['order_id'] = $order->id;
            }
            $order->payment()->createMany($data['payment']);
        }
        return $order;
    }

    public function update($id, array $data)
    {
        $order = $this->model->findOrFail($id);
        $order->update($data);
        if(isset($data['payment'])) {
            foreach ($data['payment'] as $key => $value) {
                $data['payment'][$key]['order_id'] = $order->id;
            }
            $order->payment()->delete();
            $order->payment()->createMany($data['payment']);
        }
        else{
            $order->payment()->delete();
        }
        return $order;
    }

    public function delete($id)
    {
        $order = $this->findById($id);
        if ($order) {
            return $order->delete();
        }
        return false;
    }

    // Legacy methods for backward compatibility
    public function getAllOrders()
    {
        return $this->getAll();
    }

    public function getOrderById($id)
    {
        return $this->findById($id);
    }

    public function createOrder(array $data)
    {
        return $this->create($data);
    }

    public function updateOrder($id, array $data)
    {
        return $this->update($id, $data);
    }

    public function deleteOrder($id)
    {
        return $this->delete($id);
    }
}
