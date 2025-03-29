<?php

namespace App\Http\Controllers;

use App\Services\StaffService;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    protected $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    public function index()
    {
        return response()->json($this->staffService->getAllStaff());
    }

    public function show($id)
    {
        return response()->json($this->staffService->getStaffById($id));
    }

    public function getAvailableStaffFromScheduletime(Request $request)
    {
        $time = $request->input('time');
        $schedule_ids = $request->input('schedule_id');
        $schedule_ids = is_array($schedule_ids) ? $schedule_ids : explode(',', $schedule_ids);
        $date = $request->input('date');
        $dateTimeString = $date . ' ' . $time;
        $dateTime = \Carbon\Carbon::createFromFormat('Y/m/d H:i', $dateTimeString);

        $availableStaff = $this->staffService->getAvailableStaffFromScheduletime($dateTime, $schedule_ids);
        return response()->json($availableStaff);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'profile_photo_path' => 'nullable|string|max:2048',
            'position' => 'nullable|string',
            'description' => 'nullable|string',
            'tag' => 'nullable|string',
            'has_certificate' => 'boolean',
            'status' => 'string|in:active,inactive',
            'level' => 'numeric|min:0|max:5',
            'sort' => 'nullable|integer',
        ]);

        return response()->json($this->staffService->createStaff($data), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'profile_photo_path' => 'nullable|string|max:2048',
            'position' => 'nullable|string',
            'description' => 'nullable|string',
            'tag' => 'nullable|string',
            'has_certificate' => 'boolean',
            'status' => 'string|in:active,inactive',
            'level' => 'numeric|min:0|max:5',
            'sort' => 'nullable|integer',
        ]);

        return response()->json($this->staffService->updateStaff($id, $data));
    }

    public function destroy($id)
    {
        return response()->json($this->staffService->deleteStaff($id));
    }
}
