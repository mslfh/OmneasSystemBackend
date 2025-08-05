<?php

namespace App\Services;

use App\Contracts\OrderPaymentContract;

class OrderPaymentService
{
    protected $orderPaymentRepository;

    public function __construct(OrderPaymentContract $orderPaymentRepository)
    {
        $this->orderPaymentRepository = $orderPaymentRepository;
    }

    public function getAllOrderPayments()
    {
        return $this->orderPaymentRepository->getAll();
    }

    public function getOrderPaymentById($id)
    {
        return $this->orderPaymentRepository->findById($id);
    }

    public function createOrderPayment(array $data)
    {
        return $this->orderPaymentRepository->create($data);
    }

    public function updateOrderPayment($id, array $data)
    {
        return $this->orderPaymentRepository->update($id, $data);
    }

    public function deleteOrderPayment($id)
    {
        return $this->orderPaymentRepository->delete($id);
    }

    public function getByOrderId($orderId)
    {
        return $this->orderPaymentRepository->findByOrderId($orderId);
    }
}
