<?php

namespace App\Services;

use App\Contracts\AppointmentContract;

class AppointmentService
{
    protected $appointmentRepository;

    public function __construct(AppointmentContract $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function getAllAppointments()
    {
        return $this->appointmentRepository->getAll();
    }

    public function getAppointmentsFromDate($date)
    {
        return $this->appointmentRepository->getByDate($date);
    }


    public function getAppointmentById($id)
    {
        return $this->appointmentRepository->getById($id);
    }

    public function createAppointment(array $data)
    {
        return $this->appointmentRepository->create($data);
    }

    public function updateAppointment($id, array $data)
    {
        return $this->appointmentRepository->update($id, $data);
    }

    public function deleteAppointment($id)
    {
        return $this->appointmentRepository->delete($id);
    }
}
