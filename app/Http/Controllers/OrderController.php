<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    protected $orderService;
    protected $appointmentService;

    public function __construct(OrderService $orderService, AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
        $this->orderService = $orderService;
    }


    public function index(Request $request)
    {
        $start = $request->query('start', 0);
        $count = $request->query('count', 10);
        $filter = $request->query('filter', null);
        $sortBy = $request->query('sortBy', 'id');
        $descending = $request->query('descending', false);

        $orders = $this->orderService->getPaginatedOrders($start, $count, $filter, $sortBy, $descending);

        return response()->json([
            'rows' => $orders['data'],
            'total' => $orders['total'],
        ]);
    }

    public function show($id)
    {
        return response()->json($this->orderService->getOrderById($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'status' => 'required|string',
            'payment_method' => 'required|string',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'operator_id' => 'nullable|exists:staff,id',
            'operator_name' => 'nullable|string',
            'payment_note' => 'nullable|string',
            'actual_end_time' => 'nullable',
            'actual_start_time' => 'nullable',
        ]);
        // get the appointment
        $appointment = $this->appointmentService->getAppointmentById($data['appointment_id']);
        if(!$appointment){
            return response()->json(['message' => 'Appointment not found'], 404);
        }
        // check if the appointment is already paid
        if($appointment->status == 'finished'){
            return response()->json(['message' => 'Appointment already done'], 400);
        }
        if($data['payment_method'] == 'unpaid'){
            $data['order_status'] = 'pending';
            $data['payment_status'] = 'unpaid';
        }
        else{
            $data['order_status'] = 'paid';
            $data['payment_status'] = 'success';
        }
        $appointment->status = 'finished';
        $appointment->actual_start_time = $data['actual_start_time'];
        $appointment->actual_end_time = $data['actual_end_time'];
        $appointment->save();

        unset($data['status']);
        unset($data['actual_start_time']);
        unset($data['actual_end_time']);
        return response()->json($this->orderService->createOrder($data), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'order_status' => 'sometimes|string',
            'payment_status' => 'sometimes|string',
            'payment_method' => 'sometimes|string',
            'total_amount' => 'sometimes|numeric',
            'paid_amount' => 'sometimes|numeric',
            'operator_id' => 'nullable|exists:staff,id',
            'operator_name' => 'nullable|string',
        ]);

        return response()->json($this->orderService->updateOrder($id, $data));
    }

    public function destroy($id)
    {
        return response()->json($this->orderService->deleteOrder($id));
    }
}
