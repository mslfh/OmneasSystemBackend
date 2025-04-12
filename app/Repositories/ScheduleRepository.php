<?php

namespace App\Repositories;

use App\Contracts\ScheduleContract;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleRepository implements ScheduleContract
{
    public function getAllSchedules()
    {
        return Schedule::with('staff')->get();
    }

    public function getAllAvailableSchedules()
    {
        $today = Carbon::today();
        return Schedule::whereDate('work_date', '>=', $today)
            ->where('status', '!=','off')
            ->get();
    }

    public function getAvailableScheduleByDate($date)
    {
        return Schedule::whereDate('work_date', $date)
        ->where('status', '!=','off')->get();
    }

    public function getScheduleById($id)
    {
        return Schedule::findOrFail($id);
    }

    public function createSchedule(array $data)
    {
        return Schedule::create($data);
    }

    public function updateSchedule($id, array $data)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update($data);
        return $schedule;
    }

    public function deleteSchedule($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return $schedule;
    }
}
