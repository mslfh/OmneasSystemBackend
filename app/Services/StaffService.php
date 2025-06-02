<?php

namespace App\Services;

use App\Contracts\StaffContract;
use App\Services\UserService;
use DB;

class StaffService
{
    protected $staffRepository;
    protected $userService;

    public function __construct(StaffContract $staffRepository, UserService $userService)
    {
        $this->staffRepository = $staffRepository;
        $this->userService = $userService;
    }

    public function getStaffScheduleFromDate($date, $showAll = false)
    {
        $formatDate = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
        return $this->staffRepository->getStaffScheduleFromDate($formatDate, $showAll);
    }

    public function getAvailableStaffFromScheduledate($date)
    {
        $formatDate = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
        return $this->staffRepository->getAvailableStaffFromScheduledate($formatDate);
    }

    /**
     * 获取指定时间段可用的员工（兼容原有业务调用）
     */
    public function getAvailableStaffFromScheduletime($datetime, $duration)
    {
        return $this->staffRepository->getAvailableStaffFromScheduletime($datetime, $duration);
    }

    public function getStaffScheduleAppointment($staffId, $date)
    {
        $formatDate = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
        return $this->staffRepository->getStaffScheduleAppointment($staffId, $formatDate);
    }

    public function getAllStaff()
    {
        return $this->staffRepository->getAll();
    }

    public function getStaffById($id)
    {
        return $this->staffRepository->getById($id);
    }

    public function createStaff(array $data, $avatar = null)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->createUser([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'password' => $data['password'],
            ]);

            $data['user_id'] = $user->id;

            if ($avatar) {
                $avatarName = $data['name'] . '-' . time() . '.' . $avatar->getClientOriginalExtension();
                $data['profile_photo_path'] = $avatar->storeAs('staffAvatars', $avatarName, 'public');
            }

            unset($data['avatar']);
            $staff = $this->staffRepository->create($data);

            DB::commit();
            return $staff;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateStaff($id, array $data, $avatar = null)
    {
        DB::beginTransaction();
        try {
            $staff = $this->staffRepository->getById($id);

            $this->userService->updateUser($staff->user_id, [
                'name' => $data['name'],
                'email' => $data['email'] ?? $staff->user->email,
                'phone' => $data['phone'],
                'password' => $data['password'],
            ]);

            if ($avatar) {
                $avatarName = $data['name'] . '-' . time() . '.' . $avatar->getClientOriginalExtension();
                if ($staff->profile_photo_path) {
                    \Storage::disk('public')->delete($staff->profile_photo_path);
                }
                $data['profile_photo_path'] = $avatar->storeAs('staffAvatars', $avatarName, 'public');
            }

            unset($data['email'], $data['avatar'], $data['phone'], $data['password']);
            $updatedStaff = $this->staffRepository->update($id, $data);

            DB::commit();
            return $updatedStaff;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteStaff($id)
    {
        $staff = $this->staffRepository->getById($id);
        $this->userService->deleteUser($staff->user_id);
        return $this->staffRepository->delete($id);
    }

}
