<?php

namespace App\Http\Controllers;

use App\Services\AppointmentService;
use App\Services\ServiceAppointmentService;
use App\Services\StaffService;
use App\Services\SmsService;
use App\Services\UserService;
use App\Services\SystemSettingService;
use App\Services\NotificationService;

use Carbon\Carbon;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AppointmentController extends BaseController
{
    protected $appointmentService;
    protected $serviceAppointmentService;
    protected $userService;
    protected $staffService;

    protected $systemSettingService;

    protected $smsService;

    protected $notificationService;

    public function __construct(
        AppointmentService $appointmentService,
        ServiceAppointmentService $serviceAppointmentService,
        UserService $userService,
        StaffService $staffService,
        SmsService $smsService,
        SystemSettingService $systemSettingService,
        NotificationService $notificationService
    ) {
        $this->appointmentService = $appointmentService;
        $this->serviceAppointmentService = $serviceAppointmentService;
        $this->userService = $userService;
        $this->staffService = $staffService;
        $this->smsService = $smsService;
        $this->systemSettingService = $systemSettingService;
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $start = $request->query('start', 0);
        $count = $request->query('count', 10);
        $filter = $request->query('filter', null);
        $sortBy = $request->query('sortBy', 'id');
        $descending = $request->query('descending', false);

        $appointments = $this->appointmentService->getPaginatedAppointments($start, $count, $filter, $sortBy, $descending);

        return response()->json([
            'rows' => $appointments['data'],
            'total' => $appointments['total'],
        ]);
    }

    public function getUserBookingHistory(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:users,id',
        ]);
        return response()->json($this->appointmentService->getUserBookingHistory($data['id']));
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
                    'expected_end_time' => Carbon::createFromFormat('Y-m-d H:i:s', $service->booking_time)
                        ->addMinutes($service->service_duration),
                    'package_title' => $service->package_title,
                    'service_id' => $service->service_id,
                    'service_title' => $service->service_title,
                    'service_duration' => $service->service_duration,
                    'service_price' => $service->service_price,
                    'customer_name' => $service->customer_name,
                    'comments' => $appointment->comments,
                    'appointment_id' => $appointment->id,
                    'customer_first_name' => $appointment->customer_first_name,
                    'customer_last_name' => $appointment->customer_last_name,
                    'customer_phone' => $appointment->customer_phone,
                    'customer_email' => $appointment->customer_email,
                    'tag' => $appointment->tag,
                    'status' => $appointment->status,
                    'actual_start_time' => $appointment->actual_start_time,
                    'actual_end_time' => $appointment->actual_end_time,
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
        $appointmentData['tag'] = implode(',', $data['tag']);
        unset($appointmentData['customer_service']);
        unset($appointmentData['booking_date']);
        $userData = [];
        if ($data['is_first']) {
            $userData = [
                'name' => $data['customer_service'][0]['customer_name'],
                'first_name' => $data['customer_first_name'] ?? '',
                'last_name' => $data['customer_last_name'] ?? '',
                'phone' => $data['customer_phone'] ?? '',
                'email' => $data['customer_email'] ?? '',
                'password' => bcrypt($data['customer_phone']),
            ];
        }
        // Create the appointment
        DB::beginTransaction();

        if ($data['customer_service'][0]['staff']['id'] == 0) {
            $appointmentData['status'] = 'unassigned';
        }
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
            if ($serviceData["staff"]['id'] == 0) {
                $recommendedstaff = $this->staffService->getAvailableStaffFromScheduletime(
                    $serviceData['booking_time'],
                    $serviceData['service_duration'],
                )->first();
                $serviceData['staff_id'] = $recommendedstaff->id ?? '0';
                $serviceData['staff_name'] = $recommendedstaff->name ?? '';
            } else {
                $serviceData['staff_id'] = $serviceData["staff"]['id'];
                $serviceData['staff_name'] = $serviceData["staff"]['name'];
            }
            $this->appointmentService->createServiceAppointment($serviceData);
        }
        if ($data['is_first']) {
            $this->userService->createUser($userData);
        }
        DB::commit();
        return response()->json($appointment->load('services'), 201);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json(['error' => 'Failed to create appointment'], 500);
        // }
    }

    public function takeBreakAppointment(Request $request)
    {
        $data = $request->all();
        $appointmentData = [
            'status' => 'break',
            'booking_time' => $data['date'] . ' ' . $data['time'],
        ];
        $appointmentServiceData = [
            'booking_time' => $data['date'] . ' ' . $data['time'],
            'service_id' => 0,
            'package_id' => 0,
            'service_title' => $data['service_title'],
            'service_duration' => $data['service_duration'],
            'staff_id' => $data['staff_id'],
            'staff_name' => $data['staff_name'],
            'status' => 'break',
            'customer_name' => $data['staff_name'],
            'comments' => $data['comments'],
        ];
        // Create the appointment
        DB::beginTransaction();
        // try {
        $appointment = $this->appointmentService->createAppointment($appointmentData);
        // Create associated service appointments
        $appointmentServiceData['appointment_id'] = $appointment->id;
        $this->appointmentService->createServiceAppointment($appointmentServiceData);
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
        try {
            if ($data['customer_service'][0]['staff_id'] == 0) {
                $appointmentData['status'] = 'unassigned';
            }
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
                if ($serviceData["staff_id"] == 0) {
                    $recommendedstaff = $this->staffService->getAvailableStaffFromScheduletime(
                        $serviceData['booking_time'],
                        $serviceData['service_duration'],
                    )->first();
                    $serviceData['any_therapist'] = true;
                    $serviceData['staff_id'] = $recommendedstaff->id ?? '0';
                    $serviceData['staff_name'] = $recommendedstaff->name ?? '';
                }
                $this->appointmentService->createServiceAppointment($serviceData);
            }
            DB::commit();
            // Send SMS
            $this->sendAppointmentSms(
                $appointment->customer_phone,
                $appointment->booking_time,
                $serviceData
            );
            // dd('ok');
            return response()->json($appointment->load('services'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create appointment','msg' => $e->getMessage()], 500);
        }
    }

    private function sendAppointmentSms($phone, $booking_time,$serviceData)
    {
        $booking_reminder_setting = $this->systemSettingService->getSettingByKey('booking_reminder')->value;
        // Send Booking Successfully SMS
        if ($booking_reminder_setting == "true") {
            $smsMassage = $this->systemSettingService->getSettingByKey('booking_reminder_msg')->value;
            $smsMassage = $this->formatMassage($smsMassage,$serviceData);
            $smsResponse =  $this->smsService->sendSms(
                $smsMassage,
                [$phone],
            );
            if ($smsResponse) {
                $this->notificationService->createBookingNotification($smsResponse,$serviceData);
            }
        }
        //Send reminder SMS based on the reminder interval
        $reminder_interval = (int) $this->systemSettingService->getSettingByKey('reminder_interval')->value;
        if ($reminder_interval > 0) {
            $schedule_time = Carbon::createFromFormat('Y/m/d H:i', $booking_time);
            $today = Carbon::now()->format('Y-m-d');
            //Skip reminder SMS if booking time is today
            if ($today == $schedule_time->format('Y-m-d')) {
                return;
            }
            // Send reminder SMS
            else{
                $reminder_time = Carbon::createFromFormat('Y/m/d H:i', $booking_time, 'Australia/Sydney')
                    ->subHours($reminder_interval);
                // Ensure the comparison is also in Australia/Sydney timezone
                if ($reminder_time < Carbon::now('Australia/Sydney')) {
                    return;
                }
                $reminderMassage = $this->systemSettingService->getSettingByKey('reminder_msg')->value;
                $reminderMassage = $this->formatMassage($reminderMassage,$serviceData);
                $smsResponse = $this->smsService->sendSms(
                    $reminderMassage,
                    [$phone],
                    $reminder_time->format('Y-m-d H:i:s')
                );
                if ($smsResponse) {
                    $this->notificationService->createBookingNotification($smsResponse,$serviceData,'Appintment Reminder');
                }
            }
        }
    }

    private function formatMassage($smsMassage, $serviceData)
    {
        // Extract booking date and time
        $bookingDateTime = Carbon::createFromFormat('Y/m/d H:i', $serviceData['booking_time']);
        $formattedDate = $bookingDateTime->format('l, F j'); // e.g., Friday, May 22
        $formattedTime = $bookingDateTime->format('g:i A'); // e.g., 10:00 AM
        $names = explode(' ', $serviceData['customer_name'], 2); // Split into first and last name
        $firstName = $names[0] ?? ''; // First part
        $lastName = $names[1] ?? '';  // Second part (if exists)
        // Replace placeholders in the message
        $smsMassage = str_replace('{first_name}', $firstName, $smsMassage);
        $smsMassage = str_replace('{last_name}', $lastName, $smsMassage);
        $smsMassage = str_replace('{date}', $formattedDate, $smsMassage);
        $smsMassage = str_replace('{time}', $formattedTime, $smsMassage);
        $smsMassage = str_replace('{service}', $serviceData['service_title'] ?? '', $smsMassage);
        $smsMassage = str_replace('{duration}', $serviceData['service_duration'] . ' minutes', $smsMassage);
        $smsMassage = str_replace('{therapist}', $serviceData['any_therapist'] ? 'Any Therapist' : $serviceData['staff_name'], $smsMassage);
        return $smsMassage;
    }

    public function sendSms($text, $phone_number, $schedule_time = null)
    {
        $result = $this->smsService->sendSms($text, $phone_number, $schedule_time);
        return $result;
    }

    public function update(Request $request, $id)
    {
        $appointmentData = $request->validate([
            'booking_time' => 'nullable|string',
            'booking_date' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'customer_first_name' => 'nullable|string',
            'customer_last_name' => 'nullable|string',
            'is_first' => 'nullable|boolean',
            'customer_phone' => 'nullable|string',
            'customer_email' => 'nullable|email',
            'customer_comments' => 'nullable|string',
            'tag' => 'nullable|string',
            'comments' => 'nullable|string',
            'actual_start_time' => 'nullable|string',
            'actual_end_time' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
        $serviceData = [];
        $appointment = $this->appointmentService->getAppointmentById($id);
        if (isset($appointmentData['booking_date']) && isset($appointmentData['booking_time'])) {
            $booking_time = $appointmentData['booking_date'] . ' ' . $appointmentData['booking_time'] . ":00";
            unset($appointmentData['booking_time']);
            unset($appointmentData['booking_date']);
            if ($appointment->booking_time != $booking_time) {
                $appointmentData['booking_time'] = $booking_time;
                $serviceData['booking_time'] = $booking_time;
            }
        }
        if (isset($appointmentData['actual_start_time'])) {
            $serviceData['booking_time'] = $appointmentData['actual_start_time'];
        }
        $serviceAppointment = $appointment->services->first();

        $inputService = $request->input('service') ?? [];
        $staff = $request->input('staff') ?? [];

        if (isset($inputService['id'])) {
            if ($serviceAppointment->id != $inputService['id']) {
                $service = Service::with('package')->findOrFail($inputService['id']);
                $serviceData['service_id'] = $service->id;
                $serviceData['package_id'] = $service->package_id;
                $serviceData['package_title'] = $service->package->title;
                $serviceData['package_hint'] = $service->package->hint;
                $serviceData['service_id'] = $service->id;
                $serviceData['service_title'] = $service->title;
                $serviceData['service_description'] = $service->description;
                $serviceData['service_duration'] = $service->duration;
                $serviceData['service_price'] = $service->price;
                $serviceData['comments'] = $appointmentData['comments'] ?? '';
            }
        }

        if (isset($staff['id'])) {
            if ($serviceAppointment->staff_id != $staff['id']) {
                $serviceData['staff_id'] = $staff['id'];
                $serviceData['staff_name'] = $staff['name'];
            }
        }
        if (isset($appointmentData['customer_name'])) {
            $serviceData['customer_name'] = $appointmentData['customer_name'];
        }
        $this->serviceAppointmentService->updateServiceAppointment($serviceAppointment->id, $serviceData);
        return response()->json($this->appointmentService->updateAppointment($id, $appointmentData));
    }

    public function destroy($id)
    {
        $this->appointmentService->deleteAppointment($id);
        return response()->json(null, 204);

    }
}
