<?php

namespace App\Repositories;

use App\Contracts\ScheduleHistoryContract;
use App\Models\ScheduleHistory;

class ScheduleHistoryRepository implements ScheduleHistoryContract
{
    public function getAll()
    {
        return ScheduleHistory::all();
    }

    public function getById($id)
    {
        return ScheduleHistory::findOrFail($id);
    }

    public function create(array $data)
    {
        return ScheduleHistory::create($data);
    }

    public function update($id, array $data)
    {
        $scheduleHistory = ScheduleHistory::findOrFail($id);
        $scheduleHistory->update($data);
        return $scheduleHistory;
    }

    public function delete($id)
    {
        $scheduleHistory = ScheduleHistory::findOrFail($id);
        $scheduleHistory->delete();
        return $scheduleHistory;
    }
}
