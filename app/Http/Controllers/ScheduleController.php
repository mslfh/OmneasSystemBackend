<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Services\ScheduleService;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    protected $scheduleService;
    protected $appointmentService;

    public function __construct(ScheduleService $scheduleService, AppointmentService $appointmentService)
    {
        $this->scheduleService = $scheduleService;
        $this->appointmentService = $appointmentService;
    }

    public function index()
    {
        return response()->json($this->scheduleService->getAllSchedules());
    }

    public function show($id)
    {
        return response()->json($this->scheduleService->getScheduleById($id));
    }

    public function getUnavailableTimeFromShedules(Request $request)
    {
        $date = $request->input('date');

        $formatDate = Carbon::createFromFormat('Y/m/d', $date);
        $hasBookingTime = [];
        // Get existing appointments of this schedule
        $existingAppointments = $this->appointmentService->getAppointmentsFromDate($formatDate);
        // Get booking time and staff from Appointments
        foreach ($existingAppointments as $appointment) {
            $min_duration = 0;
            // Get the min duration of each service
            foreach ($appointment->services as $service) {
                if ($min_duration == 0 || $service->service_duration < $min_duration) {
                    $min_duration = $service->service_duration;
                }
            }
            $hasBookingTime[] = [
                'start_time' => Carbon::parse($appointment->booking_time)->format('H:i'),
                'end_time' => Carbon::parse($appointment->booking_time)->addMinutes($min_duration)->format('H:i'),
            ];
        }
        return response()->json($hasBookingTime);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required',
            'work_date' => 'required',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'break_start_time' => 'nullable|string',
            'break_end_time' => 'nullable|string',
        ]);
        $wordDateBegin = Carbon::createFromFormat('Y/m/d', $data['work_date']['from']);
        $wordDateEnd = Carbon::createFromFormat('Y/m/d', $data['work_date']['to']);

        for($wordDateBegin; $wordDateBegin->lte($wordDateEnd); $wordDateBegin->addDay()) {

            $insert_data = $data;
            $insert_data['work_date'] = $wordDateBegin->format('Y/m/d');
            $insert_data['status'] = 'active';
            $this->scheduleService->createSchedule($insert_data);
        }
        return response()->json(
            [
                'message' => 'Schedule created successfully',
                'data' => '',
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'staff_id' => 'sometimes|exists:staff,id',
            'work_date' => 'sometimes|date',
            'start_time' => 'sometimes|string',
            'end_time' => 'sometimes|string',
            'break_start_time' => 'nullable|string',
            'break_end_time' => 'nullable|string',
            'status' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        return response()->json($this->scheduleService->updateSchedule($id, $data));
    }

    public function destroy($id)
    {
        $this->scheduleService->deleteSchedule($id);
        return response()->json(null, 204);
    }

    public function getAvailableShedules()
    {
        $availableSchedules = [];
        $staffNumbers = 3;

        // Group schedules by Date
        $groupedSchedules = $this->scheduleService->getAllAvailableSchedules()->groupBy(function ($schedule) {
            return Carbon::parse($schedule->work_date)->format('Y-m-d');
        });

        // Get existing appointments
        $existingAppointments = $this->appointmentService->getAllAppointments()->groupBy(function ($schedule) {
            return Carbon::parse($schedule->booking_time)->format('Y-m-d');
        })->map(function ($appointments) {
            return $appointments->count();
        })->toArray();


        // Loop through each date and add appointment states
        foreach ($groupedSchedules as $date => $schedules) {
            $bookingState = 'free';
            if (isset($existingAppointments[$date])) {
                $bookingState = $staffNumbers - $existingAppointments[$date] <= 1 ? 'nearFull' : 'hasBooking';
            }
            $availableSchedules[$date] = [
                'schedules_id' => $schedules->pluck('id')->toArray(),
                'date' =>Carbon::parse($date)->format('Y/m/d'),
                'status' => $bookingState,
            ];
        }

        return response()->json(collect($availableSchedules)->values());
    }
}
