<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_profiles');
    }
}
