<?php

namespace App\Repositories;

use App\Models\OrderAddition;
use App\Contracts\OrderAdditionContract;

class OrderAdditionRepository implements OrderAdditionContract
{
    /**
     * Get all orderAdditions
     */
    public function getAll()
    {
        return OrderAddition::all();
    }

    /**
     * Find orderAddition by ID
     */
    public function findById($id)
    {
        return OrderAddition::find($id);
    }

    /**
     * Create new orderAddition
     */
    public function create(array $data)
    {
        return OrderAddition::create($data);
    }

    /**
     * Update orderAddition
     */
    public function update($id, array $data)
    {
        $orderAddition = OrderAddition::find($id);
        if ($orderAddition) {
            $orderAddition->update($data);
            return $orderAddition;
        }
        return null;
    }

    /**
     * Delete orderAddition
     */
    public function delete($id)
    {
        $orderAddition = OrderAddition::find($id);
        if ($orderAddition) {
            return $orderAddition->delete();
        }
        return false;
    }
}
