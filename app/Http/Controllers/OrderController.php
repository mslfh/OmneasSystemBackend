<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\VoucherService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends BaseController
{
    protected $orderService;
    protected $voucherService;

    public function __construct(
        OrderService $orderService,
        VoucherService $voucherService
    ) {
        $this->orderService = $orderService;
        $this->voucherService = $voucherService;
    }


    public function index(Request $request)
    {
        $start = $request->query('start', 0);
        $count = $request->query('count', 10);
        $filter = $request->query('filter', null);
        $selected = $request->query('selected', null);
        $sortBy = $request->query('sortBy', 'id');
        $descending = $request->query('descending', false);

        $filter = $filter ? json_decode($filter, true) : null;
        $selected = $selected ? json_decode($selected, true) : null;

        $orders = $this->orderService->getPaginatedOrders($start, $count, $filter, $sortBy, $descending, $selected);

        return response()->json([
            'rows' => $orders['data'],
            'total' => $orders['total'],
        ]);
    }

    public function show($id)
    {
        return response()->json($this->orderService->getOrderById($id));
    }

    public function store(Request $request)
    {
        try {
            $order = $this->orderService->createOrder($request->all());
            return response()->json($order, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create order', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Place a new order for customer
     */
    public function placeOrder(Request $request)
    {
        try {

            $data = $request->all();

            // 调用服务层创建订单
            $order = $this->orderService->placeOrder($data);

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order placed successfully'
            ], 201);

        } catch (ValidationException $e) {
            return $this->sendError('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Failed to place order', [$e->getMessage()], 500);
        }
    }



    /**
     * Place a new order for staff
     */
    public function placeStaffOrder(Request $request)
    {
        try {

            $data = $request->all();

            // 调用服务层创建订单
            $order = $this->orderService->placeStaffOrder($data);

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order placed successfully'
            ], 201);

        } catch (ValidationException $e) {
            return $this->sendError('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Failed to place order', [$e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'order_status' => 'sometimes|string',
            'payment_status' => 'sometimes|string',
            'payment_method' => 'sometimes|string',
            'total_amount' => 'sometimes|numeric',
            'paid_amount' => 'sometimes|numeric',
            'operator_id' => 'nullable|exists:staff,id',
            'operator_name' => 'nullable|string',
        ]);

        return response()->json($this->orderService->updateOrder($id, $data));
    }

    public function destroy($id)
    {
        return response()->json($this->orderService->deleteOrder($id));
    }
}
