<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\VoucherService;
use DB;
use Illuminate\Http\Request;

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

    public function placeOrder(Request $request)
    {
        return response()->json(['message' => 'Order placed successfully'], 200);

        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'order_status' => 'required|string',
            'payment_method' => 'required|string',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'operator_id' => 'nullable|exists:staff,id',
            'operator_name' => 'nullable|string',
            'payment_note' => 'nullable|string',
            'split_payment' => 'nullable',
            'voucher_code' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Set payment status based on payment method
            if ($data['payment_method'] == 'unpaid') {
                $data['payment_status'] = 'pending';
            } else if ($data['payment_method'] == 'split_payment' && $data['order_status'] == 'pending') {
                $data['payment_status'] = 'partially_paid';
            } else {
                $data['payment_status'] = 'paid';
            }

            // Handle voucher if provided
            if (isset($data['voucher_code'])) {
                $voucherData = $this->voucherService->verifyVoucher($data['voucher_code']);
                if ($voucherData['status'] == 'error') {
                    DB::rollBack();
                    return response()->json(['message' => $voucherData['message']], 400);
                }
                $voucher = $voucherData['data'];
                if ($voucher->remaining_amount < $data['total_amount']) {
                    $voucher->remaining_amount = 0;
                } else {
                    $voucher->remaining_amount -= $data['total_amount'];
                }
                $voucher->save();
                $data['payment_note'] = ($data['payment_note'] ?? '') . ' Voucher Code: ' . $voucher->code;
            }

            // Handle split payment
            if ($data['split_payment']) {
                $payment = [];
                foreach ($data['split_payment'] as $index => $split_payment) {
                    $payment[$index]['paid_by'] = $split_payment['method']['value'];
                    if ($split_payment['method']['label'] != 'Unpaid') {
                        $payment[$index]['status'] = 'Paid';
                        $payment[$index]['paid_amount'] = $split_payment['amount'];
                    } else {
                        $payment[$index]['status'] = 'Unpaid';
                        $payment[$index]['paid_amount'] = 0;
                    }
                    $payment[$index]['total_amount'] = $split_payment['amount'];
                    $payment[$index]['remark'] = $data['payment_note'] ?? '';
                }
                $data['payment'] = $payment;
                unset($data['split_payment']);
            }

            $order = $this->orderService->updateOrder($data['order_id'], $data);
            DB::commit();
            return response()->json($order, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to finish order', 'message' => $e->getMessage()], 500);
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
