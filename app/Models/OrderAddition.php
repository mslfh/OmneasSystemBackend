<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddition extends Model
{
    //
    protected $fillable = [
        'order_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'pickup_time',
        'extend_info',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
