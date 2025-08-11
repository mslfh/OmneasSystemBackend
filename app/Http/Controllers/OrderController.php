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
     * Place a new order for staff members
     */
    public function placeOrder(Request $request)
    {
        //Test
        return response()->json([], 200);
        try {
            // 验证请求数据
            $validatedData = $request->validate([
                'user_id' => 'nullable|integer|exists:users,id',
                'type' => 'nullable|string|in:takeaway,eat-in,delivery',
                'status' => 'nullable|string|in:pending,preparing,ready,completed,cancelled',
                'tax_rate' => 'nullable|numeric|min:0|max:100',
                'discount_amount' => 'nullable|numeric|min:0',
                'payment_method' => 'nullable|string|in:cash,pos,voucher,split',
                'tag' => 'nullable|string',
                'note' => 'nullable|string|max:500',
                'remark' => 'nullable|string|max:500',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.is_combo' => 'nullable|boolean',
                'items.*.combo_id' => 'nullable|integer|exists:combos,id',
                'items.*.is_customization' => 'nullable|boolean',
                'payments' => 'nullable|array',
                'payments.*.amount' => 'required_with:payments|numeric|min:0',
                'payments.*.payment_method' => 'required_with:payments|string|in:cash,pos,voucher,split',
                'payments.*.status' => 'nullable|string|in:pending,completed,failed',
                'payments.*.tax_rate' => 'nullable|numeric|min:0|max:100',
                'payments.*.tax_amount' => 'nullable|numeric|min:0',
            ]);

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
