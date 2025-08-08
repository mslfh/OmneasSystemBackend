<?php
namespace App\Services;

use App\Contracts\ScheduleContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;
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

    /**
     * Get all available schedules grouped by date
     *
     * @return Collection
     */
    public function getAvailableSchedules()
    {
        $availableSchedules = [];

        // Group schedules by Date
        $groupedSchedules = $this->getAllAvailableSchedules()->groupBy(function ($schedule) {
            return Carbon::parse($schedule->work_date)->format('Y-m-d');
        });
        // Loop through each date and add appointment states
        foreach ($groupedSchedules as $date => $schedules) {
            $availableSchedules[$date] = [
                'schedules_id' => $schedules->pluck('id')->toArray(),
                'date' => Carbon::parse($date)->format('Y/m/d'),
            ];
        }
        return collect($availableSchedules)->values();
    }

    public function getStaffScheduleStatistics($startDate = null, $endDate = null)
    {
        $staffSchedules = $this->scheduleRepository->getStaffScheduleStatistics($startDate, $endDate);
        $staffStatistics = [];
        if ($staffSchedules->isEmpty()) {
            return collect($staffStatistics);
        }

        // Loop through each staffSchedule and calculate their total schedule time in hours and group by date
        foreach ($staffSchedules as $staffSchedule) {
            $staffId = $staffSchedule->staff_id;
            if (!isset($staffStatistics[$staffId])) {
                $staffStatistics[$staffId] = [
                    'id' => $staffId,
                    'name' => $staffSchedule->staff->name,
                    'total_hours' => 0,
                    'schedules' => [],
                ];
            }
            $scheduleDate = Carbon::parse($staffSchedule->work_date)->format('Y-m-d');
            $startTime = Carbon::createFromFormat('H:i', $staffSchedule->start_time);
            $endTime = Carbon::createFromFormat('H:i', $staffSchedule->end_time);
            $hours = $startTime->diffInHours($endTime);

            if (!isset($staffStatistics[$staffId]['schedules'][$scheduleDate])) {
                $staffStatistics[$staffId]['schedules'][$scheduleDate] = [
                    'date' => $scheduleDate,
                    'hours' => 0,
                ];
            }
            $staffStatistics[$staffId]['schedules'][$scheduleDate]['hours'] += $hours;
            $staffStatistics[$staffId]['total_hours'] += $hours;
        }

        return collect($staffStatistics)->values();
    }
}
