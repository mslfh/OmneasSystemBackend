<?php

namespace App\Services;

use App\Contracts\ServiceAppointmentContract;

class ServiceAppointmentService
{
    protected $repository;

    public function __construct(ServiceAppointmentContract $repository)
    {
        $this->repository = $repository;
    }

    public function createServiceAppointment(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateServiceAppointment(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteServiceAppointment(int $id)
    {
        return $this->repository->delete($id);
    }

    public function getServiceAppointmentById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function getAllServiceAppointments()
    {
        return $this->repository->getAll();
    }
}
