<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'is_combo',
        'combo_id',
        'combo_item_name',
        'is_customization',
        'product_title',
        'product_second_title',
        'product_items',
        'product_price',
        'product_discount',
        'product_selling_price',
        'final_amount',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'product_id' => 'integer',
        'quantity' => 'integer',
        'is_combo' => 'boolean',
        'combo_id' => 'integer',
        'is_customization' => 'boolean',
        'product_items' => 'array',
        'customization' => 'array',
        'product_price' => 'decimal:2',
        'product_discount' => 'decimal:2',
        'product_selling_price' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }

       // Accessors & Mutators for product_items and customization
    public function getProductItemsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setProductItemsAttribute($value)
    {
        $this->attributes['product_items'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getCustomizationAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setCustomizationAttribute($value)
    {
        $this->attributes['customization'] = is_array($value) ? json_encode($value) : $value;
    }
}
