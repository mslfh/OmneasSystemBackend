<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model
{
    protected $fillable = [
        'product_id',
        'item_id',
        'quantity',
        'unit',
    ];

}
