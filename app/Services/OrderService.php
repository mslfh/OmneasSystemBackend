<?php

namespace App\Services;
use App\Contracts\OrderContract;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\Combo;

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

    public function placeOrder(array $data)
    {
        try {
            // 验证必需的字段
            if (!isset($data['items']) || empty($data['items'])) {
                throw new \Exception('订单必须包含商品项');
            }

            // 预处理订单项，通过product_id查询产品信息
            $processedItems = $this->processOrderItems($data['items']);

            // 生成唯一订单号
            $orderNumber = $this->generateOrderNumber();

            // 计算订单总金额
            $orderAmounts = $this->calculateOrderAmounts($processedItems, $data);

            // 准备订单数据
            $orderData = [
                'order_number' => $orderNumber,
                'user_id' => $data['user_id'] ?? null,
                'type' => $data['type'] ?? 'takeaway',
                'status' => $data['status'] ?? 'pending',
                'total_amount' => $orderAmounts['total_amount'],
                'tax_rate' => $data['tax_rate'] ?? 10.00,
                'tax_amount' => $orderAmounts['tax_amount'],
                'discount_amount' => $data['discount_amount'] ?? 0.00,
                'final_amount' => $orderAmounts['final_amount'],
                'paid_amount' => $data['paid_amount'] ?? $orderAmounts['final_amount'],
                'payment_method' => $data['payment_method'] ?? null,
                'tag' => $data['tag'] ?? null,
                'note' => $data['note'] ?? null,
                'remark' => $data['remark'] ?? null,
            ];

            // 创建订单
            $order = $this->orderRepository->createOrder($orderData);

            // 创建订单项
            foreach ($processedItems as $item) {
                $orderItemData = [
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'is_combo' => $item['is_combo'],
                    'combo_id' => $item['combo_id'],
                    'combo_item_name' => $item['combo_item_name'],
                    'is_customization' => $item['is_customization'],
                    'product_title' => $item['product_title'],
                    'product_second_title' => $item['product_second_title'],
                    'product_items' => $item['product_items'],
                    'product_price' => $item['product_price'],
                    'product_discount' => $item['product_discount'],
                    'product_selling_price' => $item['product_selling_price'],
                    'final_amount' => $item['final_amount'],
                ];

                OrderItem::create($orderItemData);
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

                    OrderPayment::create($paymentData);
                }
            }

            // 重新加载订单以包含关联数据
            $order = $this->orderRepository->getOrderById($order->id);

            return $order;

        } catch (\Exception $e) {
            throw new \Exception('下单失败: ' . $e->getMessage());
        }
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
            $price = $item['product_selling_price'] ?? $item['product_price'] ?? 0.00;
            $totalAmount += $price * $quantity;
        }

        // 计算折扣
        $discountAmount = $orderData['discount_amount'] ?? 0.00;
        $totalAfterDiscount = $totalAmount - $discountAmount;

        // 计算税费
        $taxRate = $orderData['tax_rate'] ?? 10.00;
        $taxAmount = ($totalAfterDiscount * $taxRate) / 100;

        // 计算最终金额
        $finalAmount = $totalAfterDiscount + $taxAmount;

        return [
            'total_amount' => round($totalAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'final_amount' => round($finalAmount, 2),
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
            if (!isset($item['product_id'])) {
                throw new \Exception('每个订单项必须包含product_id');
            }
            if (!isset($item['quantity']) || $item['quantity'] < 1) {
                throw new \Exception('每个订单项必须包含有效的数量');
            }

            $processedItem = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'is_combo' => $item['is_combo'] ?? false,
                'combo_id' => null,
                'combo_item_name' => null,
                'is_customization' => $item['is_customization'] ?? false,
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
            $product = Product::find($item['product_id']);
            if (!$product) {
                throw new \Exception("产品ID {$item['product_id']} 不存在");
            }

            // 备份产品信息到订单项
            $processedItem['product_title'] = $product->title ?? '';
            $processedItem['product_second_title'] = $product->second_title ?? '';
            $processedItem['product_items'] = json_encode($product->items ?? []);
            $processedItem['product_price'] = $product->price ?? 0.00;
            $processedItem['product_discount'] = $product->discount ?? 0.00;
            $processedItem['product_selling_price'] = $product->selling_price ?? $product->price ?? 0.00;

            // 计算该项的最终金额
            $processedItem['final_amount'] = $processedItem['product_selling_price'] * $processedItem['quantity'];

            $processedItems[] = $processedItem;
        }

        return $processedItems;
    }
}
