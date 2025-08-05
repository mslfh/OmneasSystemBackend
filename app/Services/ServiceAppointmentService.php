<?php

namespace App\Services;

use App\Contracts\ServiceAppointmentContract;

class ServiceAppointmentService
{
    protected $serviceAppointmentRepository;

    public function __construct(ServiceAppointmentContract $serviceAppointmentRepository)
    {
        $this->serviceAppointmentRepository = $serviceAppointmentRepository;
    }

    public function getAllServiceAppointments()
    {
        return $this->serviceAppointmentRepository->getAll();
    }

    public function getServiceAppointmentById($id)
    {
        return $this->serviceAppointmentRepository->findById($id);
    }

    public function createServiceAppointment(array $data)
    {
        return $this->serviceAppointmentRepository->create($data);
    }

    public function updateServiceAppointment($id, array $data)
    {
        return $this->serviceAppointmentRepository->update($id, $data);
    }

    public function deleteServiceAppointment($id)
    {
        return $this->serviceAppointmentRepository->delete($id);
    }
}
