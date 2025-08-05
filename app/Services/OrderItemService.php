<?php

namespace App\Services;

use App\Contracts\OrderItemContract;

class OrderItemService
{
    protected $orderItemRepository;

    public function __construct(OrderItemContract $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }

    public function getAllOrderItems()
    {
        return $this->orderItemRepository->getAll();
    }

    public function getOrderItemById($id)
    {
        return $this->orderItemRepository->findById($id);
    }

    public function createOrderItem(array $data)
    {
        return $this->orderItemRepository->create($data);
    }

    public function updateOrderItem($id, array $data)
    {
        return $this->orderItemRepository->update($id, $data);
    }

    public function deleteOrderItem($id)
    {
        return $this->orderItemRepository->delete($id);
    }

    public function getByOrderId($orderId)
    {
        return $this->orderItemRepository->findByOrderId($orderId);
    }
}
