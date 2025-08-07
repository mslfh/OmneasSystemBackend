<?php
namespace App\Services;

use App\Contracts\ScheduleContract;
use Carbon\Carbon;
use App\Services\ServiceAppointmentService;
use App\Services\StaffService;

class ScheduleService
{
    protected $scheduleRepository;
    protected $staffService;

    public function __construct(
        ScheduleContract $scheduleRepository,
        StaffService $staffService

    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->staffService = $staffService;
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

    public function insertSchedules(array $data)
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $data['start_date']);
        $endDate = Carbon::createFromFormat('Y-m-d', $data['end_date']);
        $weekDays = collect($data['week_days']);

        for ($currentDate = $startDate; $currentDate->lte($endDate); $currentDate->addDay()) {
            $dayName = $currentDate->format('l');

            $matchingDay = $weekDays->firstWhere('day', $dayName);
            if ($matchingDay) {
                $this->createSchedule([
                    'staff_id' => $data['staff_id'],
                    'work_date' => $currentDate->format('Y-m-d'),
                    'start_time' => $matchingDay['start_time'],
                    'end_time' => $matchingDay['end_time'],
                    'status' => 'active',
                ]);

                if (!empty($matchingDay['additional_times'])) {
                    foreach ($matchingDay['additional_times'] as $additionalTime) {
                        $this->createSchedule([
                            'staff_id' => $data['staff_id'],
                            'work_date' => $currentDate->format('Y-m-d'),
                            'start_time' => $additionalTime['start'],
                            'end_time' => $additionalTime['end'],
                            'status' => 'active',
                        ]);
                    }
                }
            }
        }

    }

    public function updateSchedule($id, array $data)
    {
        return $this->scheduleRepository->updateSchedule($id, $data);
    }

    public function deleteSchedule($id)
    {
        return $this->scheduleRepository->deleteSchedule($id);
    }


    public function getStaffScheduleStatistics()
    {
        $staffSchedules = $this->scheduleRepository->getStaffScheduleStatistics();
        $staffStatistics = [];

        foreach ($staffSchedules as $schedule) {
            $staffId = $schedule->staff_id;
            if (!isset($staffStatistics[$staffId])) {
                $staffStatistics[$staffId] = [
                    'total_schedules' => 0,
                    'available_schedules' => 0,
                    'unavailable_schedules' => 0,
                ];
            }
            $staffStatistics[$staffId]['total_schedules']++;
            if ($schedule->status === 'active') {
                $staffStatistics[$staffId]['available_schedules']++;
            } else {
                $staffStatistics[$staffId]['unavailable_schedules']++;
            }
        }

        return collect($staffStatistics)->values();
    }
}
