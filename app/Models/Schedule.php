<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function setBookingAttribute($value)
    {
        $this->attributes['booking'] = json_encode($value);
    }
    public function getBookingAttribute($value)
    {
        return json_decode($value, true);
    }
}
