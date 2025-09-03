<?php

namespace App\Services;

use App\Contracts\OrderAdditionContract;

class OrderAdditionService
{
    protected $orderAdditionRepository;

    public function __construct(OrderAdditionContract $orderAdditionRepository)
    {
        $this->orderAdditionRepository = $orderAdditionRepository;
    }

    /**
     * Get all orderAdditions
     */
    public function getAllOrderAdditions()
    {
        return $this->orderAdditionRepository->getAll();
    }

    /**
     * Get orderAddition by ID
     */
    public function getOrderAdditionById($id)
    {
        return $this->orderAdditionRepository->findById($id);
    }

    /**
     * Create new orderAddition
     */
    public function createOrderAddition(array $data)
    {
        return $this->orderAdditionRepository->create($data);
    }

    /**
     * Update orderAddition
     */
    public function updateOrderAddition($id, array $data)
    {
        return $this->orderAdditionRepository->update($id, $data);
    }

    /**
     * Delete orderAddition
     */
    public function deleteOrderAddition($id)
    {
        return $this->orderAdditionRepository->delete($id);
    }
}
