# Order Factories and Seeders

本项目已成功创建了与订单相关的工厂（Factory）和种子（Seeder）文件，并确保订单项目中的产品信息与实际产品数据保持一致。

## 核心特性

### 产品数据一致性
- 订单项目中的产品信息（标题、价格等）现在直接从实际产品数据中获取
- 确保 `product_id` 对应的产品信息与订单项目中存储的信息完全一致
- 订单总金额基于实际订单项目计算

## 创建的文件

### Factories
1. **OrderFactory.php** - 订单工厂
2. **OrderItemFactory.php** - 订单项目工厂（支持产品数据一致性）
3. **OrderPaymentFactory.php** - 订单支付工厂

### Seeders
1. **OrderSeeder.php** - 订单种子（包含订单项目和支付，确保金额计算正确）
2. **OrderItemSeeder.php** - 订单项目种子
3. **OrderPaymentSeeder.php** - 订单支付种子

## 使用方法

### 运行所有种子
```bash
php artisan db:seed
```

### 仅运行订单相关种子
```bash
php artisan db:seed --class=OrderSeeder
```

### 仅运行订单项目种子
```bash
php artisan db:seed --class=OrderItemSeeder
```

### 仅运行订单支付种子
```bash
php artisan db:seed --class=OrderPaymentSeeder
```

## 工厂功能

### OrderFactory
- 支持不同订单状态：`pending()`, `completed()`, `cancelled()`
- 支持不同订单类型：`dineIn()`, `takeaway()`, `delivery()`
- 自动计算税额、折扣和最终金额

### OrderItemFactory  
- 支持套餐项目：`combo()`
- 支持自定义项目：`customized()`
- 支持指定数量：`quantity(int $quantity)`
- **支持指定产品：`forProduct(Product $product)`**
- 自动从指定产品获取真实的标题、价格等信息
- 自动计算最终金额

### OrderPaymentFactory
- 支持不同支付状态：`pending()`, `completed()`, `failed()`, `refunded()`
- 支持不同支付方式：`cash()`, `card()`, `digitalWallet()`
- 自动计算税额

## 在 Tinker 中测试

```bash
php artisan tinker
```

然后可以运行以下命令测试工厂：

```php
// 创建基本订单
$order = \App\Models\Order::factory()->create();

// 创建已完成的堂食订单
$order = \App\Models\Order::factory()->completed()->dineIn()->create();

// 使用指定产品创建订单项目
$product = \App\Models\Product::factory()->create();
$orderItem = \App\Models\OrderItem::factory()->forProduct($product)->create();

// 验证产品信息一致性
echo "产品标题: " . $product->title . " vs 订单项目标题: " . $orderItem->product_title;

// 创建带有项目和支付的订单
$order = \App\Models\Order::factory()
    ->has(\App\Models\OrderItem::factory()->count(3))
    ->has(\App\Models\OrderPayment::factory()->completed())
    ->create();

// 创建套餐订单项目
$item = \App\Models\OrderItem::factory()->combo()->create();

// 创建现金支付
$payment = \App\Models\OrderPayment::factory()->cash()->completed()->create();
```

## 数据特点

### OrderSeeder 会创建：
- 15个已完成订单（包含订单项目和支付）
- 8个待处理订单（部分包含预付款）
- 3个已取消订单
- 5个包含自定义项目的订单
- 5个堂食订单（现金支付）
- 5个外带订单（刷卡支付）
- 5个配送订单（数字钱包支付）

### 依赖关系
- 订单需要用户（User）和产品（Product）数据
- 如果数据库中没有用户或产品，种子会自动创建
- **订单项目现在使用真实的产品信息，确保数据一致性**
- **订单总金额基于实际订单项目重新计算**
- 订单项目关联到订单和产品
- 订单支付关联到订单

## 重要改进

### 数据一致性保证
1. **产品信息一致性**: 订单项目中的 `product_title`、`product_second_title`、`product_price` 等字段现在直接从 `product_id` 对应的产品中获取
2. **金额计算准确性**: 订单的总金额现在基于实际创建的订单项目重新计算
3. **真实业务逻辑**: 模拟真实的下单流程，确保测试数据的可靠性

## 注意事项

1. 运行种子前确保数据库迁移已完成
2. OrderSeeder 包含了 OrderItem 和 OrderPayment 的创建，通常不需要单独运行其他种子
3. 所有金额计算都考虑了税率和折扣
4. 工厂支持链式调用来组合不同状态
