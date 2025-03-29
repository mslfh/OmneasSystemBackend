<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    protected $guarded = ['id'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function serviceAppointment()
    {
        return $this->hasMany(ServiceAppointment::class);
    }
}
