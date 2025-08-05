<?php
namespace App\Services;

use App\Contracts\ScheduleContract;
use Carbon\Carbon;

class ScheduleService
{
    protected $scheduleRepository;

    public function __construct(ScheduleContract $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function getAllSchedules()
    {
        return $this->scheduleRepository->getAllSchedules();
    }

    public function getSchedulesFromDateAndStaff($data)
    {
        return $this->scheduleRepository->getSchedulesFromDateAndStaff($data);
    }

    public function getAllAvailableSchedules()
    {
        return $this->scheduleRepository->getAllAvailableSchedules();
    }

    public function getAvailableScheduleByDate($date)
    {
        return $this->scheduleRepository->getAvailableScheduleByDate($date);
    }

    public function getScheduleById($id)
    {
        return $this->scheduleRepository->getScheduleById($id);
    }

    public function createSchedule(array $data)
    {
        return $this->scheduleRepository->createSchedule($data);
    }

    public function updateSchedule($id, array $data)
    {
        return $this->scheduleRepository->updateSchedule($id, $data);
    }

    public function deleteSchedule($id)
    {
        return $this->scheduleRepository->deleteSchedule($id);
    }

    public function getSchedulesByDate($date)
    {
        return $this->scheduleRepository->getSchedulesByDate($date);
    }

    public function getStaffScheduleStatistics($startDate = null, $endDate = null)
    {
        return $this->scheduleRepository->getStaffScheduleStatistics($startDate, $endDate);
    }

    public function getAvailableSchedules()
    {
        $availableSchedules = [];

        // Group schedules by Date
        $groupedSchedules = $this->getAllAvailableSchedules()->groupBy(function ($schedule) {
            return Carbon::parse($schedule->work_date)->format('Y-m-d');
        });

        // Loop through each date and format response
        foreach ($groupedSchedules as $date => $schedules) {
            $availableSchedules[$date] = [
                'schedules_id' => $schedules->pluck('id')->toArray(),
                'date' => Carbon::parse($date)->format('Y/m/d'),
            ];
        }

        return collect($availableSchedules)->values();
    }

    public function getAvailableSchedulesAndStatus()
    {
        $availableSchedules = [];

        // Group schedules by Date
        $groupedSchedules = $this->getAllAvailableSchedules()->groupBy(function ($schedule) {
            return Carbon::parse($schedule->work_date)->format('Y-m-d');
        });

        // Loop through each date and add basic status
        foreach ($groupedSchedules as $date => $schedules) {
            $availableSchedules[$date] = [
                'schedules_id' => $schedules->pluck('id')->toArray(),
                'date' => Carbon::parse($date)->format('Y/m/d'),
                'booking_state' => 'available', // Simplified - no appointment checking
            ];
        }

        return collect($availableSchedules)->values();
    }
}
