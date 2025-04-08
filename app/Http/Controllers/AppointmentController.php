<?php

namespace App\Http\Controllers;

use App\Services\AppointmentService;
use Carbon\Carbon;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AppointmentController extends BaseController
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

    public function getServiceAppointments($id)
    {
        return response()->json($this->appointmentService->getServiceAppointments($id));
    }

    public function cancelAppointments($id)
    {
        $appointment = $this->appointmentService->getAppointmentById($id);
        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }
        $appointment->status = 'cancelled';
        $appointment->save();
        return response()->json($appointment);
    }

    public function getBookedServiceByDate(Request $request)
    {
        $date = $request->input('date');
        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }
        $date = Carbon::createFromFormat('Y-m-d', $date);
        $appointments = $this->appointmentService->getAppointmentByDate($date);

        $response = [];

        foreach ($appointments as $appointment) {
            foreach ($appointment->services as $service) {
                $response[] = [
                    'id' => $service->id,
                    'staff_id' => $service->staff_id,
                    'staff_name' => $service->staff_name,
                    'booking_time' => $service->booking_time,
                    'expected_end_time' => $service->expected_end_time,
                    'package_title' => $service->package_title,
                    'service_title' => $service->service_title,
                    'service_duration' => $service->service_duration,
                    'service_price' => $service->service_price,
                    'customer_name' => $appointment->customer_first_name,
                    'comments' => $appointment->comments,

                    'appointment_id' => $appointment->id,
                    'contact_first_name' => $appointment->customer_first_name,
                    'contact_last_name' => $appointment->customer_last_name,
                    'contact_phone' => $appointment->customer_phone,
                    'contact_email' => $appointment->customer_email,
                    'tag' => $appointment->tag,
                    'status' => $appointment->status,
                ];
            }
        }

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['booking_time'] = $data['booking_date'] . ' ' . $data['booking_time'];
        $appointmentData = $data;
        unset($appointmentData['customer_service']);
        unset($appointmentData['booking_date']);
        // Create the appointment
        DB::beginTransaction();
        // try {
            $appointment = $this->appointmentService->createAppointment($appointmentData);
            // Create associated service appointments
            foreach ($data['customer_service'] as $serviceData) {

                $service = Service::with('package')->findOrFail($serviceData['service']['id']);
                $serviceData['service_id'] = $service->id;
                $serviceData['staff_id'] = $service->staff_id;
                $serviceData['package_id'] = $service->package_id;
                $serviceData['package_title'] = $service->package->title;
                $serviceData['package_hint'] = $service->package->hint;

                $serviceData['service_id'] = $service->id;
                $serviceData['service_title'] = $service->title;
                $serviceData['service_description'] = $service->description;
                $serviceData['service_duration'] = $service->duration;
                $serviceData['service_price'] = $service->price;

                $serviceData['appointment_id'] = $appointment->id;
                $serviceData['booking_time'] = $appointment->booking_time;
                $serviceData['expected_end_time'] = Carbon::parse($appointment->booking_time)->addMinutes($service->duration);

                $serviceData['staff_id'] = $serviceData['staff']['id']??'0';
                $serviceData['staff_name'] = $serviceData['staff']['name']??'';
                $this->appointmentService->createServiceAppointment($serviceData);
            }
            DB::commit();
            return response()->json($appointment->load('services'), 201);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json(['error' => 'Failed to create appointment'], 500);
        // }
    }

    public function makeAppointment(Request $request)
    {
        $data = $request->all();
        $data['booking_time'] = $data['booking_date'] . ' ' . $data['booking_time'];
        $appointmentData = $data;
        unset($appointmentData['customer_service']);
        unset($appointmentData['booking_date']);
        $appointmentData['tag'] = implode(',', $data['tag']);
        // Create the appointment
        DB::beginTransaction();
        // try {
            $appointment = $this->appointmentService->createAppointment($appointmentData);
            // Create associated service appointments
            foreach ($data['customer_service'] as $serviceData) {

                $service = Service::with('package')->findOrFail($serviceData['service_id']);
                $serviceData['package_id'] = $service->package_id;
                $serviceData['package_title'] = $service->package->title;
                $serviceData['package_hint'] = $service->package->hint;

                $serviceData['service_id'] = $service->id;
                $serviceData['service_title'] = $service->title;
                $serviceData['service_description'] = $service->description;
                $serviceData['service_duration'] = $service->duration;
                $serviceData['service_price'] = $service->price;

                $serviceData['appointment_id'] = $appointment->id;
                $serviceData['booking_time'] = $appointment->booking_time;
                $serviceData['expected_end_time'] = Carbon::parse($appointment->booking_time)->addMinutes($service->duration);
                $serviceData['staff_id'] = $serviceData["staff_id"]??'0';
                $serviceData['staff_name'] = $serviceData["staff_name"]??'';
                $this->appointmentService->createServiceAppointment($serviceData);
            }
            DB::commit();
            return response()->json($appointment->load('services'), 201);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json(['error' => 'Failed to create appointment'], 500);
        // }
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
