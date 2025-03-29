<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Staff extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $appends = ['profile_photo_url'];

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo ? asset('storage/' . $this->profile_photo) : asset('default-avatar.png');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function bookingServices()
    {
        return $this->hasMany(
            ServiceAppointment::class,
            'staff_id',
            'id'
        );
    }

    public function appointments()
    {
        return $this->belongsToMany(
            Appointment::class,
            'service_appointments',
            'staff_id',
            'appointment_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
