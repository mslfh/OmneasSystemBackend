<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'profile_id',
        'additional_info',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'profile_id' => 'integer',
        'additional_info' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
