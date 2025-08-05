<?php

namespace App\Repositories;

use App\Contracts\OrderContract;
use App\Models\Order;

class OrderRepository implements OrderContract
{
    public function getAllOrders()
    {
        return Order::with('payment')->get();
    }

    public function getOrderById($id)
    {
        return Order::where('id','=',$id)
        ->with('payment')
        ->first();
    }

    public function createOrder(array $data)
    {
        $order = Order::create($data);
        if(isset($data['payment'])) {
            foreach ($data['payment'] as $key => $value) {
                $data['payment'][$key]['order_id'] = $order->id;
            }
            $order->payment()->createMany($data['payment']);
        }
        return $order;
    }

    public function updateOrder($id, array $data)
    {
        $order = Order::findOrFail($id);
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

    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return $order;
    }
}
