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

    public function getOrderByAppointment($appointmentId)
    {
        return $this->orderRepository->getOrderByAppointment($appointmentId);
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

    public function getPaginatedOrders($start, $count, $filter, $sortBy, $descending)
    {
        $query = Order::query();

        if ($filter) {
            $query->whereHas('appointment', function ($q) use ($filter) {
                $q->where('customer_first_name', 'like', "%$filter%")
                ->orWhere('customer_phone', 'like', "%$filter%")
                ->orWhere('customer_email', 'like', "%$filter%")
                ->orWhere('booking_time', 'like', "%$filter%");
            });
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

    public function initAppointmentOrder ( $appointmentId,$total_amount)
    {
        $data = [
            'status' => 'pending',
            'appointment_id'  => $appointmentId,
            'payment_method' => 'unpaid',
            'total_amount' => $total_amount,
        ];
        $this->createOrder($data);
    }

     public function finishOrder ( $appointmentId, $total_amount)
    {
        $data = [
            'status' => 'pending',
            'appointment_id'  => $appointmentId,
            'payment_method' => 'unpaid',
            'total_amount' => $total_amount,
        ];
        $this->createOrder($data);
    }
}
