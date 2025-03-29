<?php
namespace App\Services;

use App\Contracts\ScheduleContract;

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

    public function getAllAvailableSchedules()
    {
        return $this->scheduleRepository->getAllAvailableSchedules();
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
}
