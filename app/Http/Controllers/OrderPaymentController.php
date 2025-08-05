<?php

namespace App\Http\Controllers;

use App\Services\OrderPaymentService;
use Illuminate\Http\Request;

class OrderPaymentController extends BaseController
{
    protected $orderPaymentService;

    public function __construct(OrderPaymentService $orderPaymentService)
    {
        $this->orderPaymentService = $orderPaymentService;
    }

    public function index()
    {
        try {
            $orderPayments = $this->orderPaymentService->getAllOrderPayments();
            return $this->sendResponse($orderPayments, 'Order payments retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving order payments', [$e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $orderPayment = $this->orderPaymentService->createOrderPayment($request->all());
            return $this->sendResponse($orderPayment, 'Order payment created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating order payment', [$e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $orderPayment = $this->orderPaymentService->getOrderPaymentById($id);
            if (!$orderPayment) {
                return $this->sendError('Order payment not found');
            }
            return $this->sendResponse($orderPayment, 'Order payment retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving order payment', [$e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $orderPayment = $this->orderPaymentService->updateOrderPayment($id, $request->all());
            if (!$orderPayment) {
                return $this->sendError('Order payment not found');
            }
            return $this->sendResponse($orderPayment, 'Order payment updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating order payment', [$e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->orderPaymentService->deleteOrderPayment($id);
            if (!$result) {
                return $this->sendError('Order payment not found');
            }
            return $this->sendResponse([], 'Order payment deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting order payment', [$e->getMessage()]);
        }
    }

    public function getByOrderId($orderId)
    {
        try {
            $orderPayments = $this->orderPaymentService->getByOrderId($orderId);
            return $this->sendResponse($orderPayments, 'Order payments retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving order payments', [$e->getMessage()]);
        }
    }
}
