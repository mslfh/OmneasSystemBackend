<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'total_amount',
        'remaining_amount',
        'type',
        'discount_value',
        'notes',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];
}
