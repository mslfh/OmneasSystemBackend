<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Services\ScheduleService;
use App\Services\AppointmentService;
use App\Services\ServiceAppointmentService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Services\SystemSettingService;

class ScheduleController extends BaseController
{
    protected $systemSettingService;
    protected $scheduleService;
    protected $serviceAppointmentService;
    protected $appointmentService;

    public function __construct(
        ScheduleService $scheduleService,
        AppointmentService $appointmentService,
        ServiceAppointmentService $serviceAppointmentService,
        SystemSettingService $systemSettingService
    ) {
        $this->scheduleService = $scheduleService;
        $this->serviceAppointmentService = $serviceAppointmentService;
        $this->appointmentService = $appointmentService;
        $this->systemSettingService = $systemSettingService;
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

        // Get all staff schedules
        $allStaffSchedules = Staff::select('id', 'status')
            ->with('schedules', function ($query) use ($formatDate) {
                $query->select('id', 'staff_id', 'start_time', 'end_time', 'work_date', 'status')
                    ->where('status', 'active')
                    ->where('work_date', '=', $formatDate->format('Y-m-d'));
            })
            ->where('status', 'active')
            ->get();

        $allSchedules = $allStaffSchedules->pluck('schedules')->flatten();
        $minScheduleTime = $allSchedules->min('start_time');
        $maxScheduleTime = $allSchedules->max('end_time');
        $unavilableTime = [
            'start_time' => $minScheduleTime,
            'end_time' => $maxScheduleTime,
            'unavilable_time' => [],
        ];
        //if date is today
        if ($formatDate->isToday()) {
            //check today has schedule
            $todaySchedule = $this->scheduleService->getAvailableScheduleByDate($formatDate->format('Y-m-d'));
            if ($todaySchedule->isEmpty()) {
                $unavilableTime['unavilable_time'][] = [
                    'start_time' => $minScheduleTime,
                    'end_time' => $maxScheduleTime
                ];
                return response()->json($unavilableTime);
            } else {
                $unavilableTime['unavilable_time'][] = [
                    'start_time' => $minScheduleTime,
                    'end_time' => Carbon::now('Australia/Sydney')->format('H:i'),
                ];
            }
        }
        // Get existing appointments of this schedule
        $existingAppointments = $this->serviceAppointmentService->getAppointmentsFromDate($formatDate);
        $scheduleIntervals = collect($allSchedules)->map(function ($item) {
            return [
                'start' => Carbon::createFromFormat('H:i', $item->start_time),
                'end' => Carbon::createFromFormat('H:i', $item->end_time),
            ];
        })->sortBy('start')->values();

        $noScheduleIntervals = $this->findNoScheduleIntervals(
            $minScheduleTime,
            $maxScheduleTime,
            $scheduleIntervals
        );
        foreach ($noScheduleIntervals as $noScheduleInterval) {
            $unavilableTime['unavilable_time'][] = [
                'start_time' => $noScheduleInterval['start_time'],
                'end_time' => $noScheduleInterval['end_time'],
            ];
        }
        // Get all busy intervals
        $busyIntervals = $this->findBusyIntervals($existingAppointments, $allSchedules);
        foreach ($busyIntervals as $busyInterval) {
            $unavilableTime['unavilable_time'][] = [
                'start_time' => $busyInterval['start_time'],
                'end_time' => $busyInterval['end_time'],
            ];
        }
        return response()->json($unavilableTime);
    }

    public function getUnavailableTimeFromStaff(Request $request)
    {
        $date = $request->input('date');
        $staff = $request->input('staff_id');

        $formatDate = Carbon::createFromFormat('Y/m/d', $date);

        // Get staff schedules and appointments
        $allStaffSchedules = Staff::select('id', 'status')
            ->where('id', $staff)
            ->with('schedules', function ($query) use ($formatDate) {
                $query->select('id', 'staff_id', 'start_time', 'end_time', 'work_date', 'status')
                    ->where('status', 'active')
                    ->where('work_date', '=', $formatDate->format('Y-m-d'));
            })
            ->with('bookingServices', function ($query) use ($formatDate) {
                $query->select('id', 'staff_id', 'booking_time', 'expected_end_time')
                    ->where('booking_time', '>=', $formatDate->format('Y-m-d 00:00:00'))
                    ->where('booking_time', '<=', $formatDate->format('Y-m-d 23:59:59'));
            })
            ->where('status', 'active')
            ->get();

        $allSchedules = $allStaffSchedules->pluck('schedules')->flatten();
        $minScheduleTime = $allSchedules->min('start_time');
        $maxScheduleTime = $allSchedules->max('end_time');
        $unavilableTime = [
            'start_time' => $minScheduleTime,
            'end_time' => $maxScheduleTime,
            'unavilable_time' => [],
        ];
        //if date is today
        if ($formatDate->isToday()) {
            //check today has schedule
            $todaySchedule = $this->scheduleService->getAvailableScheduleByDate($formatDate->format('Y-m-d'));
            if ($todaySchedule->isEmpty()) {
                $unavilableTime['unavilable_time'][] = [
                    'start_time' => $minScheduleTime,
                    'end_time' => $maxScheduleTime
                ];
                return response()->json($unavilableTime);
            } else {
                $unavilableTime['unavilable_time'][] = [
                    'start_time' => $minScheduleTime,
                    'end_time' => Carbon::now('Australia/Sydney')->format('H:i'),
                ];
            }
        }
        // Get all no schedules Interval
        $scheduleIntervals = collect($allSchedules)->map(function ($item) {
            return [
                'start' => Carbon::createFromFormat('H:i', $item->start_time),
                'end' => Carbon::createFromFormat('H:i', $item->end_time),
            ];
        })->sortBy('start')->values();

        $noScheduleIntervals = $this->findNoScheduleIntervals(
            $minScheduleTime,
            $maxScheduleTime,
            $scheduleIntervals
        );
        foreach ($noScheduleIntervals as $noScheduleInterval) {
            $unavilableTime['unavilable_time'][] = [
                'start_time' => $noScheduleInterval['start_time'],
                'end_time' => $noScheduleInterval['end_time'],
            ];
        }
        // Get all busy intervals
        $existingAppointments = $allStaffSchedules->pluck('bookingServices')->flatten();

        $busyIntervals = $this->findBusyIntervalsOfStaff($existingAppointments);
        foreach ($busyIntervals as $busyInterval) {
            $unavilableTime['unavilable_time'][] = [
                'start_time' => $busyInterval['start_time'],
                'end_time' => $busyInterval['end_time'],
            ];
        }
        return response()->json($unavilableTime);
    }

    public function findBusyIntervalsOfStaff(Collection $appointments)
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
            // 计算当前时间点的排班数
            $limit = 1;

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

    public function findNoScheduleIntervals($openingTime, $closingTime, Collection $scheduleIntervals)
    {
        $gaps = [];
        $cursor = Carbon::createFromFormat('H:i', $openingTime);
        foreach ($scheduleIntervals as $interval) {
            if ($interval['start']->gt($cursor)) {
                $gaps[] = [
                    'start_time' => $cursor->format('H:i'),
                    'end_time' => $interval['start']->format('H:i'),
                ];
            }
            if ($interval['end']->gt($cursor)) {
                $cursor = $interval['end']->copy();
            }
        }
        if ($cursor->lt($closingTime)) {
            $gaps[] = [
                'start_time' => $cursor->format('H:i'),
                'end_time' => $closingTime,
            ];
        }
        return $gaps;
    }

    public function findBusyIntervals(Collection $appointments, Collection $schedules)
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
            // 计算当前时间点的排班数
            $limit = $schedules
                ->where('start_time', '<=', $event['time']->toTimeString())
                ->where('end_time', '>=', $event['time']->toTimeString())
                ->count();

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
    public function insert(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required|integer',
            'schedule_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'week_days' => 'required|array',
            'week_days.*.day' => 'required|string',
            'week_days.*.start_time' => 'required|string',
            'week_days.*.end_time' => 'required|string',
            'week_days.*.additional_times' => 'nullable|array',
            'week_days.*.additional_times.*.start' => 'required_with:week_days.*.additional_times|string',
            'week_days.*.additional_times.*.end' => 'required_with:week_days.*.additional_times|string',
        ]);

        $startDate = Carbon::createFromFormat('Y-m-d', $data['start_date']);
        $endDate = Carbon::createFromFormat('Y-m-d', $data['end_date']);
        $weekDays = collect($data['week_days']);

        for ($currentDate = $startDate; $currentDate->lte($endDate); $currentDate->addDay()) {
            $dayName = $currentDate->format('l');

            $matchingDay = $weekDays->firstWhere('day', $dayName);
            if ($matchingDay) {
                $this->scheduleService->createSchedule([
                    'staff_id' => $data['staff_id'],
                    'work_date' => $currentDate->format('Y-m-d'),
                    'start_time' => $matchingDay['start_time'],
                    'end_time' => $matchingDay['end_time'],
                    'status' => 'active',
                ]);

                if (!empty($matchingDay['additional_times'])) {
                    foreach ($matchingDay['additional_times'] as $additionalTime) {
                        $this->scheduleService->createSchedule([
                            'staff_id' => $data['staff_id'],
                            'work_date' => $currentDate->format('Y-m-d'),
                            'start_time' => $additionalTime['start'],
                            'end_time' => $additionalTime['end'],
                            'status' => 'active',
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'message' => 'Schedule created successfully',
            'data' => '',
        ]);
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
