# 下单API更新说明

## 简化的下单接口

现在下单时只需要传递 `product_id` 和基本信息，产品的详细信息会自动从数据库查询并备份到订单中。

## 请求示例

### 最简请求
```json
{
  "items": [
    {
      "product_id": 1,
      "quantity": 2
    },
    {
      "product_id": 2,
      "quantity": 1,
      "is_combo": true,
      "combo_id": 1
    }
  ]
}
```

### 完整请求
```json
{
  "user_id": 1,
  "type": "takeaway",
  "status": "pending",
  "tax_rate": 10.00,
  "discount_amount": 5.00,
  "payment_method": "cash",
  "tag": "staff_order",
  "note": "客户要求不要辣",
  "remark": "员工下单",
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "is_combo": false
    },
    {
      "product_id": 2,
      "quantity": 1,
      "is_combo": true,
      "combo_id": 1,
      "is_customization": false
    }
  ],
  "payments": [
    {
      "amount": 56.10,
      "payment_method": "cash",
      "status": "completed"
    }
  ]
}
```

## 订单项字段说明

### 必需字段
- `product_id` (integer): 产品ID，必须存在于products表中
- `quantity` (integer): 数量，最小值为1

### 可选字段
- `is_combo` (boolean): 是否为套餐商品，默认false
- `combo_id` (integer): 套餐ID，当is_combo为true时使用
- `is_customization` (boolean): 是否为定制商品，默认false

## 自动处理的字段

系统会根据 `product_id` 自动查询并备份以下产品信息到订单项中：

- `product_title`: 产品标题
- `product_second_title`: 产品副标题
- `product_items`: 产品组成项（JSON格式）
- `product_price`: 产品原价
- `product_discount`: 产品折扣
- `product_selling_price`: 产品售价
- `final_amount`: 该项最终金额（售价 × 数量）

对于套餐商品，还会查询套餐信息：
- `combo_item_name`: 套餐名称

## 优势

1. **简化前端调用**: 前端只需要传递产品ID和数量
2. **数据一致性**: 确保订单中备份的产品信息与下单时的产品信息一致
3. **历史记录**: 即使产品信息后续发生变化，订单中的历史数据仍然准确
4. **减少错误**: 避免前端传递错误的产品信息

## 错误处理

系统会验证：
- 产品ID是否存在
- 套餐ID是否存在（当is_combo为true时）
- 数量是否有效
- 订单是否包含至少一个商品项

如果验证失败，会返回相应的错误信息。
