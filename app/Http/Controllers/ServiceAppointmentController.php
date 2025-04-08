<?php

namespace App\Http\Controllers;

use App\Models\ServiceAppointment;
use App\Services\ServiceAppointmentService;
use Illuminate\Http\Request;

class ServiceAppointmentController extends BaseController
{
    protected $serviceAppointmentService;

    public function __construct(ServiceAppointmentService $serviceAppointmentService)
    {
        $this->serviceAppointmentService = $serviceAppointmentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->serviceAppointmentService->getAllServiceAppointments());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'service_id' => 'required|exists:services,id',
            'staff_id' => 'nullable|exists:staff,id',
            'booking_time' => 'required|date',
            'expected_end_time' => 'required|date',
            'comments' => 'nullable|string',
        ]);

        return response()->json($this->serviceAppointmentService->createServiceAppointment($data), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json($this->serviceAppointmentService->getServiceAppointmentById($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceAppointment $serviceAppointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'service_id' => 'sometimes|exists:services,id',
            'staff_id' => 'nullable|exists:staff,id',
            'staff_name' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'booking_time' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);
        return response()->json($this->serviceAppointmentService->updateServiceAppointment($id, $data));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->serviceAppointmentService->deleteServiceAppointment($id);
        return response()->json(null, 204);
    }
}
