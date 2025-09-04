<?php

namespace App\Services;
use App\Contracts\OrderContract;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\Combo;
use DB;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderContract $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getAllOrders()
    {
        return $this->orderRepository->getAllOrders();
    }

    public function getOrderById($id)
    {
        return $this->orderRepository->getOrderById($id);
    }

    public function createOrder(array $data)
    {
        return $this->orderRepository->createOrder($data);
    }

    public function updateOrder($id, array $data)
    {
        return $this->orderRepository->updateOrder($id, $data);
    }

    public function deleteOrder($id)
    {
        return $this->orderRepository->deleteOrder($id);
    }

    public function getPaginatedOrders($start, $count, $filter = null, $sortBy = 'id', $descending = false, $selected = null)
    {
        $query = Order::query();

        if ($filter) {
        }

        if ($selected) {
        }

        $sortDirection = $descending ? 'desc' : 'asc';
        $query->with(['payments', 'items'])->withTrashed()->orderBy($sortBy, $sortDirection);

        $total = $query->count();
        $data = $query->skip($start)->take($count)->get();

        return [
            'data' => $data,
            'total' => $total,
        ];
    }

    /**
     * 顾客下单
     */
    public function placeOrder(array $data)
    {
        // 验证必需的字段
        if (!isset($data['items']) || empty($data['items'])) {
            throw new \Exception('订单必须包含商品项');
        }

        // 预处理订单项，通过product_id查询产品信息
        $processedItems = $this->processOrderItems($data['items']);

        $orderNo = $this->generateOrderNo();

        // 生成唯一订单号
        $orderNumber = $this->generateOrderNumber();

        // 计算订单总金额
        $orderAmounts = $this->calculateOrderAmounts($processedItems, $data);


        $additionInfo = $data['customerInfo'] ?? [];

        // 准备订单数据
        $orderData = [
            'order_number' => $orderNumber,
            'order_no' => $orderNo,
            'place_in' => $data['place_in'] ?? 'online',
            'user_id' => $data['user_id'] ?? null,
            'type' => $data['diningType'] ?? 'takeaway',
            'status' => 'pending',
            'total_amount' => $orderAmounts['total_amount'],
            'tax_rate' => $orderAmounts['tax_rate'],
            'tax_amount' => $orderAmounts['tax_amount'],
            'discount_amount' => $data['discount_amount'] ?? 0.00,
            'final_amount' => $orderAmounts['final_amount'],
            'paid_amount' => $data['paid_amount'] ?? 0.00,
            'payment_method' => $data['payment_method'] ?? null,
            'note' => $additionInfo['notes'] ?? null,
        ];

        DB::beginTransaction();

        try {
            // 创建订单
            $order = $this->orderRepository->createOrder($orderData);

            // 创建订单项
            foreach ($processedItems as $item) {
                $orderItemData = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'is_combo' => $item['is_combo'],
                    'combo_id' => $item['combo_id'],
                    'combo_item_name' => $item['combo_item_name'],
                    'is_customization' => $item['is_customization'],
                    'product_title' => $item['product_title'],
                    'product_second_title' => $item['product_second_title'],
                    'product_items' => $item['product_items'],
                    'customization' => $item['customization'],
                    'product_price' => $item['product_price'],
                    'product_discount' => $item['product_discount'],
                    'product_selling_price' => $item['product_selling_price'],
                    'final_amount' => $item['final_amount'],
                ];
                $order->items()->create($orderItemData);
            }

            if ($additionInfo) {
                $additionData = [
                    'customer_name' => $additionInfo['name'] ?? null,
                    'customer_phone' => $additionInfo['phone'] ?? null,
                    'customer_email' => $additionInfo['email'] ?? null,
                    'customer_address' => $additionInfo['address'] ?? null,
                    'pickup_time' => $additionInfo['pickup_time'] ?? null,
                    'extend_info' => $additionInfo['extend_info'] ?? null,
                ];
                $order->additions()->create($additionData);
            }

            // 如果有支付信息，创建支付记录
            if (isset($data['payments']) && !empty($data['payments'])) {
                foreach ($data['payments'] as $payment) {
                    $paymentData = [
                        'order_id' => $order->id,
                        'amount' => $payment['amount'],
                        'payment_method' => $payment['payment_method'] ?? 'cash',
                        'status' => $payment['status'] ?? 'completed',
                        'tax_rate' => $payment['tax_rate'] ?? $data['tax_rate'] ?? 10.00,
                        'tax_amount' => $payment['tax_amount'] ?? 0.00,
                    ];

                    $order->payments()->create($paymentData);
                }
            }

            // 重新加载订单以包含关联数据
            $order = $this->orderRepository->getOrderById($order->id);

            DB::commit();

            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('下单失败: ' . $e->getMessage());
        }
    }

    /**
     * 员工下单
     */
    public function placeStaffOrder(array $data)
    {
        // 验证必需的字段
        if (!isset($data['items']) || empty($data['items'])) {
            throw new \Exception('订单必须包含商品项');
        }

        // 预处理订单项，通过product_id查询产品信息
        $processedItems = $this->processStaffOrderItems($data['items']);

        //准备订单数据

        // 订单金额
        $orderPay = [];
        $orderPay['tax_rate'] = $data['tax_rate'] ?? 10.00;
        $orderPay['service_fee'] = $data['service_fee'] ?? 10.00;
        $orderPay['discount_amount'] = $data['discount_amount'] ?? 0.00;
        $orderPay['total_amount'] = $data['total_amount'] ?? 10.00;

        //保留两位小数
        $orderPay['tax_amount'] = round($orderPay['total_amount'] * ($orderPay['tax_rate'] / 100), 2);
        $orderPay['payment_method'] = $data['cash_amount'] > 0 ?
            $data['pos_amount'] > 0 ? 'cash & pos' : 'cash' : 'pos';

        //订单支付
        $payments = [];
        if ($data['cash_amount'] > 0) {
            //现金支付
            $payments[] = [
                'received_amount' => $data['cash_amount'],
                'paid_amount' => $data['cash_change'] ? $data['cash_amount'] - $data['cash_change'] : $data['cash_amount'],
                'payment_method' => 'cash',
                'status' => 'completed',
                'tax_rate' => $orderPay['tax_rate'],
                'tax_amount' => 0,
            ];
        }
        if ($data['pos_amount'] > 0) {
            //刷卡支付
            $payments[] = [
                'received_amount' => $data['pos_amount'],
                'paid_amount' => $orderPay['total_amount'],
                'payment_method' => 'pos',
                'status' => 'completed',
                'tax_rate' => $orderPay['tax_rate'],
                'tax_amount' => 0,
            ];
        }

        // 订单时间
        //"order_time": "2025-09-04T01:24:36.007675",
        $orderTime = $data['order_time'] ?? now();

        // 订单
        $orderData = [
            'order_number' => $data['order_id'],
            'order_no' => $data['order_no'],
            'type' => $data['type'] ?? 'takeaway',

            'status' => 'completed',
            'sync_status' => 'synced',
            'print_status' => $data['print_status'] ?? 'printFailed',

            'total_amount' => $orderPay['total_amount'],
            'tax_rate' => $orderPay['tax_rate'],
            'tax_amount' => $orderPay['tax_amount'],
            'discount_amount' => $orderPay['discount_amount'],
            'final_amount' => $orderPay['total_amount'],

            'paid_amount' => $orderPay['total_amount'],
            'payment_method' => $orderPay['payment_method'],
            'note' => $data['note'] ?? null,
            'synced_at' => now(),
            'created_at' => $orderTime,
        ];

        DB::beginTransaction();

        try {
            // 创建订单
            $order = $this->orderRepository->createOrder($orderData);

            // 创建订单项
            foreach ($processedItems as $item) {
                $order->items()->create($item);
            }

            // 创建支付记录
            if (!empty($payments)) {
                foreach ($payments as $payment) {
                    $order->payments()->create($payment);
                }
            }

            // 重新加载订单以包含关联数据
            $order = $this->orderRepository->getOrderById($order->id);

            DB::commit();

            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('下单失败: ' . $e->getMessage());
        }
    }

    /**
     * 获取当天的订单号
     */
    private function generateOrderNo()
    {
        $today = now()->format('Y-m-d');
        $count = Order::whereDate('created_at', $today)->count();
        $orderNo = 'A' . sprintf('%03d', $count + 1);
        return $orderNo;
    }

    /**
     * 生成唯一订单号
     */
    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $timestamp = now()->format('YmdHis');
        $random = sprintf('%04d', rand(0, 9999));
        return $prefix . $timestamp . $random;
    }

    /**
     * 计算订单金额
     */
    private function calculateOrderAmounts(array $items, array $orderData)
    {
        $totalAmount = 0;

        // 计算商品总金额
        foreach ($items as $item) {
            $quantity = $item['quantity'] ?? 1;
            $price = $item['final_amount'] ?? 0.00;
            $totalAmount += $price * $quantity;
        }
        //默认商品总金额含税
        $isHasTax = true;

        // 计算折扣
        $discountAmount = $orderData['discount_amount'] ?? 0.00;
        $totalAfterDiscount = $totalAmount - $discountAmount;

        // 计算税费
        $taxRate = $orderData['tax_rate'] ?? 10.00;
        $taxAmount = ($totalAfterDiscount * $taxRate) / 100;

        // 计算最终金额
        if ($isHasTax) {
            $finalAmount = $totalAfterDiscount;
        } else {
            $finalAmount = $totalAfterDiscount + $taxAmount;
        }

        return [
            'total_amount' => round($totalAmount, 2),
            'tax_rate' => round($taxRate, 2),
            'tax_amount' => round($taxAmount, 2),
            'final_amount' => round($finalAmount, 2),
            'is_has_tax' => $isHasTax
        ];
    }

    /**
     * 处理订单项，通过product_id查询产品信息并备份到订单项中
     */
    private function processOrderItems(array $items)
    {
        $processedItems = [];

        foreach ($items as $item) {
            // 验证必需的字段
            if (!isset($item['id'])) {
                throw new \Exception('每个订单项必须包含product_id');
            }
            if (!isset($item['quantity']) || $item['quantity'] < 1) {
                throw new \Exception('每个订单项必须包含有效的数量');
            }

            $processedItem = [
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'is_combo' => $item['is_combo'] ?? false,
                'combo_id' => null,
                'combo_item_name' => null,
                'final_amount' => $item['currentPrice'] ?? $processedItem['product_selling_price'],
                'is_customization' => $item['customizable'] ?? false,
                'customization' => $item['customizations'] ?? null,
                'product_items' => $item['ingredients'] ?? []
            ];

            // 如果是套餐商品，查询套餐信息
            if ($processedItem['is_combo'] && isset($item['combo_id'])) {
                $combo = Combo::find($item['combo_id']);
                if (!$combo) {
                    throw new \Exception("套餐ID {$item['combo_id']} 不存在");
                }
                $processedItem['combo_id'] = $combo->id;
                $processedItem['combo_item_name'] = $combo->title ?? '';
            }

            // 查询产品信息
            $product = Product::find($item['id']);
            if (!$product) {
                throw new \Exception("产品ID {$item['id']} 不存在");
            }

            // 备份产品信息到订单项
            $processedItem['product_title'] = $product->title ?? '';
            $processedItem['product_second_title'] = $product->second_title ?? '';
            $processedItem['product_price'] = $product->price ?? 0.00;
            $processedItem['product_discount'] = $product->discount ?? 0.00;
            $processedItem['product_selling_price'] = $product->selling_price ?? $product->price ?? 0.00;

            $processedItems[] = $processedItem;
        }

        return $processedItems;
    }


    /**
     * 处理订单项，通过product_id查询产品信息并备份到订单项中
     */
    private function processStaffOrderItems(array $items)
    {
        $processedItems = [];

        foreach ($items as $item) {
            // 验证必需的字段
            if (!isset($item['id'])) {
                throw new \Exception('每个订单项必须包含product_id');
            }
            if (!isset($item['quantity']) || $item['quantity'] < 1) {
                throw new \Exception('每个订单项必须包含有效的数量');
            }

            $processedItem = [
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'final_amount' => $item['price'],
                'customization' => $item['options'] ?? null,
            ];

            // 查询产品信息
            $product = Product::find($item['id']);
            if (!$product) {
                throw new \Exception("产品ID {$item['id']} 不存在");
            }

            // 备份产品信息到订单项
            $processedItem['product_title'] = $product->title ?? '';
            $processedItem['product_second_title'] = $product->second_title ?? '';
            $processedItem['product_price'] = $product->price ?? 0.00;
            $processedItem['product_discount'] = $product->discount ?? 0.00;
            $processedItem['product_selling_price'] = $product->selling_price ?? $product->price ?? 0.00;

            $processedItems[] = $processedItem;
        }

        return $processedItems;
    }

    public function fetchNewOrder($latestId)
    {
        return Order::with([
            'items' => function ($query) {
                $query->select('id', 'order_id', 'product_id', 'quantity', 'product_title', 'product_selling_price', 'final_amount', 'customization');
            },
            'additions' => function ($query) {
                $query->select('id', 'order_id', 'customer_name', 'customer_phone', 'pickup_time', 'extend_info');
            },
            'payments'
        ])
            ->where('id', '>', $latestId)
            ->orderBy('id', 'asc')
            ->get();
    }
}
