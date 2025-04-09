<?php

namespace App\Repositories;

use App\Contracts\ServiceAppointmentContract;
use App\Models\ServiceAppointment;

class ServiceAppointmentRepository implements ServiceAppointmentContract
{
    public function create(array $data)
    {
        return ServiceAppointment::create($data);
    }

    public function update(int $id, array $data)
    {
        $serviceAppointment = ServiceAppointment::findOrFail($id);
        if(isset($data['booking_time'])) {
            $data['expected_end_time'] = \Carbon\Carbon::parse($data['booking_time'])->addMinutes($serviceAppointment->service_duration);
        }
        $serviceAppointment->update($data);
        return $serviceAppointment;
    }

    public function delete(int $id)
    {
        $serviceAppointment = ServiceAppointment::findOrFail($id);
        $serviceAppointment->delete();
    }

    public function findById(int $id)
    {
        return ServiceAppointment::findOrFail($id);
    }

    public function getAll()
    {
        return ServiceAppointment::all();
    }

    public function getAppointmentsFromDate($date)
    {
        return ServiceAppointment::whereDate('booking_time', $date)->get();
    }
}
