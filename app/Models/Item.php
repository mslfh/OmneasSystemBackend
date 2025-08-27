<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Item extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'type',
        'description',
        'price',
        'extra_price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'extra_price' => 'decimal:2',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
