<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Services\ScheduleService;
use App\Services\AppointmentService;
use App\Services\ServiceAppointmentService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ScheduleController extends BaseController
{
    protected $scheduleService;
    protected $serviceAppointmentService;
    protected $appointmentService;
    public function __construct(
        ScheduleService $scheduleService,
        AppointmentService $appointmentService,
        ServiceAppointmentService $serviceAppointmentService
    ) {
        $this->scheduleService = $scheduleService;
        $this->serviceAppointmentService = $serviceAppointmentService;
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

        $unavilableTime = [];
        //if date is today
        if ($formatDate->isToday()) {
            $unavilableTime[] = [
                'start_time' => "08:00",
                'end_time' => Carbon::now('Australia/Sydney')->format('H:i'),
            ];
        }
        // Get existing appointments of this schedule
        $existingAppointments = $this->serviceAppointmentService->getAppointmentsFromDate($formatDate);

        $allStaff = Staff::with('schedules')
            ->where('status', 'active')
            ->get();
        $maxWorkDate = $allStaff
            ->pluck('schedules')
            ->flatten()
            ->max('work_date');
        $staffNumber = 0;
        if ($maxWorkDate && $maxWorkDate < $formatDate->format('Y-m-d')) {
            $staffNumber = $allStaff->count();
        } else {
            $staffNumber = Staff::with('schedules')
                ->where('status', 'active')->whereHas('schedules', function ($query) use ($formatDate) {
                    $query->where('work_date', '=', $formatDate->format('Y-m-d'));
                })->count();
        }
        $busyIntervals = $this->findBusyIntervals( $existingAppointments,$staffNumber );
        foreach ($busyIntervals as $busyInterval) {
            $unavilableTime[] = [
                'start_time' => $busyInterval['start_time'],
                'end_time' => $busyInterval['end_time'],
            ];
        }
        return response()->json($unavilableTime);
    }

    public function findBusyIntervals(Collection $appointments, int $limit)
    {
        // 1. 提取所有时间点
        $events = [];
        foreach ($appointments as $appointment) {
            $events[] = ['time' => Carbon::parse($appointment['booking_time']), 'type' => 'start'];
            $events[] = ['time' => Carbon::parse($appointment['expected_end_time']), 'type' => 'end'];
        }

        // 2. 按时间排序（先按时间，start优先）
        usort($events, function ($a, $b) {
            if ($a['time']->eq($b['time'])) {
                return $a['type'] === 'start' ? -1 : 1;
            }
            return $a['time']->lt($b['time']) ? -1 : 1;
        });

        // 3. 遍历时间点，计算并发预约数
        $busyPeriods = [];
        $currentCount = 0;
        $startTime = null;

        foreach ($events as $event) {
            if ($event['type'] === 'start') {
                $currentCount++;
            } else {
                $currentCount--;
            }

            // 进入满足条件的时间区间
            if ($currentCount >= $limit && !$startTime) {
                $startTime = $event['time'];
            }

            // 离开满足条件的时间区间
            if ($currentCount < $limit && $startTime) {
                $busyPeriods[] = [
                    'start_time' => $startTime->toTimeString(),
                    'end_time' => $event['time']->toTimeString(),
                ];
                $startTime = null;
            }
        }

        return $busyPeriods;
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
            'remark' => 'nullable|string',
        ]);
        $wordDateBegin = Carbon::createFromFormat('Y/m/d', $data['work_date']['from'] ?? $data['work_date']);
        $wordDateEnd = Carbon::createFromFormat('Y/m/d', $data['work_date']['to'] ?? $data['work_date']);

        for ($wordDateBegin; $wordDateBegin->lte($wordDateEnd); $wordDateBegin->addDay()) {
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
                'date' => Carbon::parse($date)->format('Y/m/d'),
                'status' => $bookingState,
            ];
        }

        return response()->json(collect($availableSchedules)->values());
    }
}
