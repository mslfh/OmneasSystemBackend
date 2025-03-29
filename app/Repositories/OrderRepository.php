<?php

namespace App\Repositories;

use App\Contracts\OrderContract;
use App\Models\Order;

class OrderRepository implements OrderContract
{
    public function getAllOrders()
    {
        return Order::all();
    }

    public function getOrderById($id)
    {
        return Order::findOrFail($id);
    }

    public function createOrder(array $data)
    {
        return Order::create($data);
    }

    public function updateOrder($id, array $data)
    {
        $order = Order::findOrFail($id);
        $order->update($data);
        return $order;
    }

    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return $order;
    }
}
