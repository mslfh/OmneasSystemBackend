<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComboProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'combo_item_id',
        'is_default',
        'extra_price',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'combo_item_id' => 'integer',
        'is_default' => 'boolean',
        'extra_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function comboItem()
    {
        return $this->belongsTo(ComboItem::class);
    }
}
