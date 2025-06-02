<?php

namespace App\Repositories;

use App\Contracts\StaffContract;
use App\Models\Staff;

class StaffRepository implements StaffContract
{
    public function getAll()
    {
        return Staff::all();
    }

    public function getStaffScheduleFromDate($date)
    {
        return Staff::select('id', 'name', 'status')
            ->where('status', 'active')
            ->whereHas('schedules', function ($query) use ($date) {
                $query->where('schedules.status', '=', 'active')
                    ->where('schedules.work_date', '=', $date->format('Y-m-d'));
            })
            ->with('schedules', function ($query) use ($date) {
                $query->select('id', 'staff_id', 'start_time', 'end_time', 'work_date', 'status')
                    ->where('schedules.status', '=', 'active')
                    ->where('schedules.work_date', '=', $date->format('Y-m-d'));
            })
            ->get();
    }

    public function getAvailableStaffFromScheduledate($date)
    {
        return Staff::where('status', 'active')
            ->whereHas('schedules', function ($query) use ($date) {
                $query->where('schedules.status', '=', 'active')
                    ->where('schedules.work_date', '=', $date->format('Y-m-d'));
            })
            ->get();
    }

    public function getAvailableStaffFromScheduletime($dateTime, $duration)
    {
        $formatStartTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $dateTime);
        $formatEndTime = $formatStartTime->copy()->addMinutes($duration);

        return Staff::where('status', 'active')
            ->whereHas('schedules', function ($query) use ($formatStartTime) {
                $query->where('work_date', '=', $formatStartTime->format('Y-m-d'));
            })
            ->whereDoesntHave('bookingServices', function ($query) use ($formatStartTime, $formatEndTime) {
                $query->where(function ($subQuery) use ($formatStartTime, $formatEndTime) {
                    $subQuery->where('booking_time', '<', $formatEndTime)
                             ->whereRaw('DATE_ADD(booking_time, INTERVAL service_duration MINUTE) > ?', [$formatStartTime]);
                });
            })
            ->get();
    }

    public function getStaffScheduleAppointment($staffId, $formatDate)
    {
        return Staff::select('id', 'status')
            ->where('id', $staffId)
            ->whereHas('schedules', function ($query) use ($formatDate) {
                $query->where('status', 'active')
                    ->where('work_date', '=', $formatDate->format('Y-m-d'));
            })
            ->with('schedules', function ($query) use ($formatDate) {
                $query->select('id', 'staff_id', 'start_time', 'end_time', 'work_date', 'status')
                    ->where('status', 'active')
                    ->where('work_date', '=', $formatDate->format('Y-m-d'));
            })
            ->with('bookingServices', function ($query) use ($formatDate) {
                $query->select('id', 'staff_id', 'booking_time', 'service_duration')
                    ->where('booking_time', '>=', $formatDate->format('Y-m-d 00:00:00'))
                    ->where('booking_time', '<=', $formatDate->format('Y-m-d 23:59:59'));
            })
            ->where('status', 'active')
            ->get();
    }

    public function getById($id)
    {
        $staff = Staff::findOrFail($id);
        return $staff;
    }

    public function create(array $data)
    {
        return Staff::create($data);
    }

    public function update($id, array $data)
    {
        $staff = Staff::findOrFail($id);
        $staff->update($data);
        return $staff;
    }

    public function delete($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();
        return $staff;
    }
}
