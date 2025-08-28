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
        'quantity_price',
    ];

    // Accessors & Mutators for replacement_list
    public function getReplacementListAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setReplacementListAttribute($value)
    {
        $this->attributes['replacement_list'] = is_array($value) ? json_encode($value) : $value;
    }

    // Accessors & Mutators for replacement_diff
    public function getReplacementDiffAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setReplacementDiffAttribute($value)
    {
        $this->attributes['replacement_diff'] = is_array($value) ? json_encode($value) : $value;
    }

    // Accessors & Mutators for replacement_extra
    public function getReplacementExtraAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setReplacementExtraAttribute($value)
    {
        $this->attributes['replacement_extra'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getQuantityPriceAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setQuantityPriceAttribute($value)
    {
        $this->attributes['quantity_price'] = is_array($value) ? json_encode($value) : $value;
    }

}
