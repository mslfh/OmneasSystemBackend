<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
       use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'extra_cost',
    ];

    protected $casts = [
        'extra_cost' => 'decimal:2',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
