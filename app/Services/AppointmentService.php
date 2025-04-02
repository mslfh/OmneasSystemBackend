<?php

namespace App\Services;

use App\Contracts\AppointmentContract;
use App\Services\ServiceAppointmentService;

class AppointmentService
{
    protected $appointmentRepository;
    protected $serviceAppointmentService;

    public function __construct(AppointmentContract $appointmentRepository, ServiceAppointmentService $serviceAppointmentService)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->serviceAppointmentService = $serviceAppointmentService;
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

    public function createServiceAppointment(array $data)
    {
        return $this->serviceAppointmentService->createServiceAppointment($data);
    }

    public function getAppointmentByDate($date)
    {
        return $this->appointmentRepository->getByDate($date);
    }
}
