<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'title',
        'second_title',
        'acronym',
        'viewable',
        'description',
        'tip',
        'price',
        'discount',
        'selling_price',
        'stock',
        'status',
        'image',
        'image_list',
        'tag',
        'sort',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer',
        'sort' => 'integer',
        'is_featured' => 'boolean',
        'image_list' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function items()
    {
        return $this->hasMany(ProductItem::class);
    }

    public function profiles()
    {
        return $this->belongsToMany(Profile::class, 'product_profiles');
    }

    public function comboItems()
    {
        return $this->hasMany(ComboItem::class);
    }

    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'combo_products');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
