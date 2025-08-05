<?php

namespace App\Http\Controllers;

use App\Services\OrderItemService;
use Illuminate\Http\Request;

class OrderItemController extends BaseController
{
    protected $orderItemService;

    public function __construct(OrderItemService $orderItemService)
    {
        $this->orderItemService = $orderItemService;
    }

    public function index()
    {
        try {
            $orderItems = $this->orderItemService->getAllOrderItems();
            return $this->sendResponse($orderItems, 'Order items retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving order items', [$e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $orderItem = $this->orderItemService->createOrderItem($request->all());
            return $this->sendResponse($orderItem, 'Order item created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating order item', [$e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $orderItem = $this->orderItemService->getOrderItemById($id);
            if (!$orderItem) {
                return $this->sendError('Order item not found');
            }
            return $this->sendResponse($orderItem, 'Order item retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving order item', [$e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $orderItem = $this->orderItemService->updateOrderItem($id, $request->all());
            if (!$orderItem) {
                return $this->sendError('Order item not found');
            }
            return $this->sendResponse($orderItem, 'Order item updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating order item', [$e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->orderItemService->deleteOrderItem($id);
            if (!$result) {
                return $this->sendError('Order item not found');
            }
            return $this->sendResponse([], 'Order item deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting order item', [$e->getMessage()]);
        }
    }

    public function getByOrderId($orderId)
    {
        try {
            $orderItems = $this->orderItemService->getByOrderId($orderId);
            return $this->sendResponse($orderItems, 'Order items retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving order items', [$e->getMessage()]);
        }
    }
}
