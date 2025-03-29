<?php

namespace App\Contracts;

interface ScheduleContract
{
    public function getAllSchedules();
    public function getAllAvailableSchedules();
    public function getScheduleById($id);
    public function createSchedule(array $data);
    public function updateSchedule($id, array $data);
    public function deleteSchedule($id);
}
