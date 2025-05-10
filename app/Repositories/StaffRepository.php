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

    public function getAvailableStaffFromScheduledate($date)
    {
        $formatDate = \Carbon\Carbon::createFromFormat('Y/m/d', $date);
        return Staff::where('status', 'active')
            ->whereHas('schedules', function ($query) use ($formatDate) {
                $query->where('work_date', '=', $formatDate->format('Y-m-d'));
            })
            ->get();
    }

    public function getStaffScheduleFromDate($date)
    {
        $formatDate = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
        return Staff::where('status', 'active')
            ->with('schedules', function ($query) use ($formatDate) {
                $query->select('id', 'staff_id', 'work_date', 'start_time', 'end_time')
                      ->where('work_date', '=', $formatDate->format('Y-m-d'));
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
}
