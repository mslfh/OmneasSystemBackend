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

    public function getPaginatedOrders($start, $count, $filter = null, $sortBy = 'id', $descending = false, $selected = null)
    {
        $query = Order::query();

        if ($filter) {
            if ($filter['field'] == "payment_method") {
                $query->where('payment_method', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "payment_status") {
                $query->where('payment_status', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "operator_name") {
                $query->where('operator_name', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "order_number") {
                $query->where('order_number', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "total_amount") {
                $query->where('total_amount', '=', $filter['value']);
            }
        }

        if ($selected) {
            if ($selected['field'] == "deleted") {
                $query->where('deleted_at', '!=', null);
            } else if ($selected['field'] == "pending") {
                $query->where('payment_status', 'pending');
            } else if ($selected['field'] == "completed") {
                $query->where('payment_status', 'completed');
            } else if ($selected['field'] == "cancelled") {
                $query->where('payment_status', 'cancelled');
            }
        }

        $sortDirection = $descending ? 'desc' : 'asc';
        $query->with(['payment', 'items'])->withTrashed()->orderBy($sortBy, $sortDirection);

        $total = $query->count();
        $data = $query->skip($start)->take($count)->get();

        return [
            'data' => $data,
            'total' => $total,
        ];
    }
}
