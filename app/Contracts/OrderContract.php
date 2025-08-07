<?php

namespace App\Contracts;

interface OrderContract
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    // Keep existing specific methods for backward compatibility
    public function getAllOrders();
    public function getOrderById($id);
    public function createOrder(array $data);
    public function updateOrder($id, array $data);
    public function deleteOrder($id);
}
