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

    public function getAvailableStaffFromScheduletime($dateTime)
    {
        $formatDateTime = \Carbon\Carbon::createFromFormat('Y/m/d H:i', $dateTime);
        return Staff::where('status', 'active')
            ->whereHas('schedules', function ($query) use ($formatDateTime) {
                $query->where('work_date', '=', $formatDateTime->format('Y-m-d'))
                    ->where('start_time', '<=', $formatDateTime->format('H:i'))
                    ->where('end_time', '>=', $formatDateTime->format('H:i'));
            })
            ->whereDoesntHave('bookingServices', function ($query) use ($formatDateTime) {
                $query->where('booking_time', '<=', $formatDateTime)
                    ->where('expected_end_time', '>=', $formatDateTime);
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
