<?php

namespace App\Contracts;

interface StaffContract
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getStaffScheduleFromDate($date);
    public function getAvailableStaffFromScheduledate($date);
    public function getStaffScheduleAppointment($staffId, $formatDate);
}
