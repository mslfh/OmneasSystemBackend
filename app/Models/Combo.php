<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Combo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'original_price',
        'discount',
        'tax_rate',
        'tax_amount',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(ComboItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'combo_products');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
