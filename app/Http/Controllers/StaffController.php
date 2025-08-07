<?php

namespace App\Http\Controllers;

use App\Services\StaffService;
use App\Services\UserService;
use Illuminate\Http\Request;

class StaffController extends BaseController
{
    protected $staffService;
    protected $userService;

    public function __construct(StaffService $staffService, UserService $userService)
    {
        $this->staffService = $staffService;
        $this->userService = $userService;
    }

    public function index()
    {
        return response()->json($this->staffService->getAllStaff());
    }

    public function show($id)
    {
        return response()->json($this->staffService->getStaffById($id));
    }


    /**
     * Get staff and schedule from date.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStaffScheduleFromDate(Request $request)
    {
        $date = $request->input('date');
        $showAll = $request->input('showAll')? true : false;
        return response()->json($this->staffService->getStaffScheduleFromDate($date, $showAll));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'position' => 'nullable|string',
            'status' => 'string|in:active,inactive',
            'description' => 'nullable|string',
            'tag' => 'nullable|string',
            'has_certificate' => 'nullable|string',
            'level' => 'nullable|numeric',
            'sort' => 'nullable|integer',
            'email' => 'nullable|email|max:255',
            'password' => 'required|string|min:8',
            'avatar' => 'nullable|file',
        ]);

        return response()->json($this->staffService->createStaff($data, $request->file('avatar')), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'position' => 'nullable|string',
            'status' => 'string|in:active,inactive',
            'description' => 'nullable|string',
            'tag' => 'nullable|string',
            'has_certificate' => 'nullable|string',
            'level' => 'nullable|numeric',
            'sort' => 'nullable|integer',
            'email' => 'nullable|email|max:255',
            'password' => 'required|string|min:8',
            'avatar' => 'nullable|file',
        ]);

        return response()->json($this->staffService->updateStaff($id, $data, $request->file('avatar')));
    }

    public function destroy($id)
    {
        return response()->json($this->staffService->deleteStaff($id));
    }
}
