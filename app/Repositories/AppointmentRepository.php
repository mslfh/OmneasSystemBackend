<?php

namespace App\Repositories;

use App\Contracts\AppointmentContract;
use App\Models\Appointment;

class AppointmentRepository implements AppointmentContract
{
    public function getAll()
    {
        return Appointment::all()->load('services');
    }
    public function getByDate($date)
    {
        return Appointment::whereDate('booking_time', $date)
        ->whereNot(
            'status',
            'cancelled'
        )->with('services')->orderBy('booking_time')->get();
    }
    public function getUserBookingHistory($data)
    {
        $name = $data['name'] ;
        return Appointment::where(
            'customer_first_name', $data['first_name']
        )
        ->orWhere(
            'customer_last_name', $data['last_name']
        )
        ->orWhere(
            "customer_phone",$data['phone']
        )
        ->orWhereHas('services',function ($query) use ( $name){
            $query->where('customer_name', $name);
        }
        )
        ->with('services')->orderBy('booking_time')->get();
    }

    public function getById($id)
    {
        return Appointment::findOrFail($id)->load('services');
    }

    public function create(array $data)
    {
        return Appointment::create($data);
    }

    public function update($id, array $data)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($data);
        return $appointment;
    }

    public function getServiceAppointments($id)
    {
        return Appointment::findOrFail($id)->services;
    }

    public function delete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->services()->delete();
        $appointment->delete();
        return $appointment;
    }
}
