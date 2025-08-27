<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomization extends Model
{
    protected $fillable = [
        'product_id',
        'item_id',
        'mode',
        'replacement_list',
        'replacement_diff',
        'replacement_extra',
        'extra_price',
    ];
}
