<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComboItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'combo_id',
        'name',
        'description',
        'type',
        'min_select',
        'max_select',
    ];

    protected $casts = [
        'combo_id' => 'integer',
        'min_select' => 'integer',
        'max_select' => 'integer',
    ];

    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'combo_products', 'combo_item_id', 'product_id');
    }
}
