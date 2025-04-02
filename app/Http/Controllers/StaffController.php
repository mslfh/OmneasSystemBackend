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
        $date = $request->input('date');
        $dateTimeString = $date . ' ' . $time;
        $availableStaff = $this->staffService->getAvailableStaffFromScheduletime($dateTimeString);
        return response()->json($availableStaff);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'profile_photo_path' => 'nullable|string|max:2048',
            'position' => 'nullable|string',
            'description' => 'nullable|string',
            'tag' => 'nullable|string',
            'has_certificate' => 'boolean',
            'level' => 'numeric|min:0|max:5',
            'sort' => 'nullable|integer',
            'name' => 'required|string|max:255',
        ]);
        //Create a user for this staff
        $userData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required',
            'password' => 'required',
            'phone' => 'nullable|string|max:15'
        ]);
        $user = \App\Models\User::create($userData);
        $data['user_id'] = $user->id;
        $data['status'] = "active";
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
            'name' => 'sometimes|string|max:255',
        ]);
        return response()->json($this->staffService->updateStaff($id, $data));
    }

    public function destroy($id)
    {
        return response()->json($this->staffService->deleteStaff($id));
    }
}
