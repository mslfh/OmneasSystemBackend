<?php

namespace App\Services;
use App\Contracts\OrderContract;
use App\Models\Order;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderContract $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getAllOrders()
    {
        return $this->orderRepository->getAllOrders();
    }

    public function getOrderById($id)
    {
        return $this->orderRepository->getOrderById($id);
    }

    public function createOrder(array $data)
    {
        return $this->orderRepository->createOrder($data);
    }

    public function updateOrder($id, array $data)
    {
        return $this->orderRepository->updateOrder($id, $data);
    }

    public function deleteOrder($id)
    {
        return $this->orderRepository->deleteOrder($id);
    }

    public function getPaginatedOrders($start, $count, $filter = null, $sortBy = 'id', $descending = false)
    {
        $query = Order::query();

        if ($filter) {
            $query->where('payment_method', 'like', "%$filter%")
                  ->orWhere('payment_status', 'like', "%$filter%")
                  ->orWhere('operator_name', 'like', "%$filter%");
        }

        $sortDirection = $descending ? 'desc' : 'asc';
        $query->orderBy($sortBy, $sortDirection);

        $total = $query->count();
        $data = $query->skip($start)->take($count)->with('payment')->get();

        return [
            'data' => $data,
            'total' => $total,
        ];
    }
}
