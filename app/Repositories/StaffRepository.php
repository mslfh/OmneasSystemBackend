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

    public function getAvailableStaffFromScheduletime($dateTime, $schedule_ids)
    {
        return Staff::where('status', 'active')
            ->whereHas('schedules', function ($query) use ($schedule_ids) {
                $query->whereIn('id', $schedule_ids);
            })
            ->whereDoesntHave('bookingServices',
            function ($query) use ($dateTime) {
                $query->where('booking_time', '<=', $dateTime)
                    ->where('expected_end_time', '>=', $dateTime);
            })
            ->get();
    }

    public function getById($id)
    {
        return Staff::findOrFail($id);
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
