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

    public function getStaffScheduleFromDate($date, $showAll = false)
    {
        $query = Staff::select('id', 'name', 'status')
            ->where('status', 'active');
        if (!$showAll) {
            $query = $query->whereHas('schedules', function ($query) use ($date) {
                $query->where('schedules.status', '=', 'active')
                    ->where('schedules.work_date', '=', $date->format('Y-m-d'));
            });
        }
        $query = $query->with('bookingServices', function ($query) use ($date) {
            $query->select('id', 'staff_id')->whereDate('booking_time', '=', $date->format('Y-m-d'));
        })
            ->with('schedules', function ($query) use ($date) {
                $query->select('id', 'staff_id', 'start_time', 'end_time', 'work_date', 'status','remark')
                    ->where('schedules.status', '=', 'active')
                    ->where('schedules.work_date', '=', $date->format('Y-m-d'));
            })
            ->get();
        return $query;
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
            ->whereHas('schedules', function ($query) use ($formatStartTime,$formatEndTime) {
                $query->where('work_date', $formatStartTime->format('Y-m-d'))
                    ->whereRaw('STR_TO_DATE(CONCAT(work_date, " ", start_time), "%Y-%m-%d %H:%i") <= ?', [$formatStartTime])
                    ->whereRaw('STR_TO_DATE(CONCAT(work_date, " ", end_time), "%Y-%m-%d %H:%i") >= ?', [$formatEndTime]);
            })
            ->whereDoesntHave('bookingServices', function ($query) use ($formatStartTime, $formatEndTime) {
                $query->where(function ($subQuery) use ($formatStartTime, $formatEndTime) {
                    $subQuery->where('booking_time', '<', $formatEndTime)
                        ->whereRaw('DATE_ADD(booking_time, INTERVAL service_duration MINUTE) > ?', [$formatStartTime]);
                });
            })
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

    public function getStaffIncomeStatistics($startDate, $endDate, $staffId = null)
    {
        $query = Staff::select('id', 'name')
            ->where('status', 'active');

        if ($staffId) {
            $query->where('id', $staffId);
        }
        return $query->get();
    }
}
