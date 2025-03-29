<?php

namespace App\Http\Controllers;

use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index()
    {
        return response()->json($this->appointmentService->getAllAppointments());
    }

    public function show($id)
    {
        return response()->json($this->appointmentService->getAppointmentById($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'booking_time' => 'required|date',
            'customer_first_name' => 'required|string',
            'customer_last_name' => 'required|string',
            'is_first' => 'boolean',
            'customer_phone' => 'nullable|string',
            'customer_email' => 'nullable|email',
            'customer_comments' => 'nullable|string',
            'tag' => 'nullable|string',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'status' => 'nullable|string|in:pending,confirmed,cancelled,in_progress,completed',
        ]);

        return response()->json($this->appointmentService->createAppointment($data), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'booking_time' => 'sometimes|date',
            'customer_first_name' => 'sometimes|string',
            'customer_last_name' => 'sometimes|string',
            'is_first' => 'boolean',
            'customer_phone' => 'nullable|string',
            'customer_email' => 'nullable|email',
            'customer_comments' => 'nullable|string',
            'tag' => 'nullable|string',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'status' => 'nullable|string|in:pending,confirmed,cancelled,in_progress,completed',
        ]);

        return response()->json($this->appointmentService->updateAppointment($id, $data));
    }

    public function destroy($id)
    {
        $this->appointmentService->deleteAppointment($id);
        return response()->json(null, 204);
    }
}
