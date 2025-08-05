<?php

namespace App\Repositories;

use App\Models\OrderPayment;
use App\Contracts\OrderPaymentContract;

class OrderPaymentRepository implements OrderPaymentContract
{
    protected $model;

    public function __construct(OrderPayment $orderPayment)
    {
        $this->model = $orderPayment;
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
        $orderPayment = $this->findById($id);
        if ($orderPayment) {
            $orderPayment->update($data);
            return $orderPayment;
        }
        return null;
    }

    public function delete($id)
    {
        $orderPayment = $this->findById($id);
        if ($orderPayment) {
            return $orderPayment->delete();
        }
        return false;
    }

    public function findByOrderId($orderId)
    {
        return $this->model->where('order_id', $orderId)->get();
    }
}
