<?php

namespace App\Services;

use App\Contracts\AppointmentContract;
use App\Services\ServiceAppointmentService;
use App\Models\Appointment;
use App\Models\User;

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
    public function getPaginatedAppointments($start, $count, $filter, $sortBy, $descending)
    {
        $query = Appointment::query();

        if ($filter) {
            $query->where('name', 'like', "%$filter%") // Example filter
                  ->orWhere('status', 'like', "%$filter%");
        }
        $sortDirection = $descending ? 'desc' : 'asc';
        $query->with('services')->orderBy($sortBy, $sortDirection);

        $total = $query->count();
        $data = $query->skip($start)->take($count)->get();

        return [
            'data' => $data,
            'total' => $total,
        ];
    }

    public function getAppointmentsFromDate($date)
    {
        return $this->appointmentRepository->getByDate($date);
    }

    public function getUserBookingHistory($id)
    {
        $user =  User::find($id);
        $data=[
            'name' => $user->name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone' => $user->phone,
        ];
        return $this->appointmentRepository->getUserBookingHistory($data);
    }

    public function getAppointmentById($id)
    {
        return $this->appointmentRepository->getById($id);
    }

    public function getServiceAppointments($id)
    {
        return $this->appointmentRepository->getServiceAppointments($id);
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
