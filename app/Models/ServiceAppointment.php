<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAppointment extends Model
{
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
