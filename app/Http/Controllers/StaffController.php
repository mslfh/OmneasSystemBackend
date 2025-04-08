<?php

namespace App\Http\Controllers;

use App\Services\StaffService;
use App\Services\UserService;
use DB;
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
            'name' => 'required|string|max:255',
            'position' => 'nullable|string',
            'status' => 'string|in:active,inactive',
            'description' => 'nullable|string',
            'tag' => 'nullable|string',
            'level' => 'numeric|min:0|max:5',
            'has_certificate' => 'nullable|string',
            'sort' => 'nullable|integer',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:8',
            'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        //Create a user for this staff
        $userData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required',
            'password' => 'required',
            'phone' => 'nullable|string|max:15'
        ]);
        DB::beginTransaction();
        try {
            $user = $this->userService->createUser($userData);
            $data['user_id'] = $user->id;
            if ($request->hasFile('avatar')) {
                $avatarName = $data['name'] . '-' . time() . '.' . $request->file('avatar')->getClientOriginalExtension();
                $profile_photo_path = $request->file('avatar')
                    ->storeAs('staffAvatars', $avatarName, 'public');
            }
            unset($data['avatar']);
            $data['profile_photo_path'] = $profile_photo_path ?? null;
            $staff = $this->staffService->createStaff($data);
            DB::commit();
            return response()->json($staff, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create staff'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'position' => 'nullable|string',
            'description' => 'nullable|string',
            'tag' => 'nullable|string',
            'has_certificate' => 'nullable|string',
            'status' => 'string|in:active,inactive',
            'level' => 'numeric|min:0|max:5',
            'sort' => 'nullable|integer',
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8',
            'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        DB::beginTransaction();
        // try {
            $staff = $this->staffService->getStaffById($id);

            $userData = [
                'name' => $data['name'] ?? $staff->user->name,
                'phone' => $data['phone'] ?? $staff->user->phone,
                'password' => $data['password'] ?? $staff->user->password,
            ];
            $this->userService->updateUser($staff->user_id, $userData);

            if ($request->hasFile('avatar')) {
                $avatarName = $data['name'] . '-' . time() . '.' . $request->file('avatar')->getClientOriginalExtension();
                //delete old avatar
                if ($staff->profile_photo_path) {
                    \Storage::disk('public')->delete($staff->profile_photo_path);
                }
                $data['profile_photo_path'] = $request->file('avatar')
                    ->storeAs('staffAvatars', $avatarName, 'public');
            }
            unset($data['email'], $data['avatar'], $data['phone'], $data['password']);
            $updatedStaff = $this->staffService->updateStaff($id, $data);

            DB::commit();
            return response()->json($updatedStaff);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json(['error' => 'Failed to update staff'], 500);
        // }
    }
    public function destroy($id)
    {
        $staff = $this->staffService->getStaffById($id);
        $this->userService->deleteUser($staff->user_id);
        return response()->json($this->staffService->deleteStaff($id));
    }
}
