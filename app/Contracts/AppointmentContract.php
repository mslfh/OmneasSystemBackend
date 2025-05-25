<?php

namespace App\Contracts;

interface AppointmentContract
{
    public function getAll();
    public function getByDate($date);
    public function getById($id);
    public function getServiceAppointments($id);
    public function getUserBookingHistory($userId);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
