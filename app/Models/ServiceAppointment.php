<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceAppointment extends Model
{
    use  SoftDeletes;
    protected $guarded = ['id'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

}
