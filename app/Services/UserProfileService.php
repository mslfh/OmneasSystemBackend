<?php

namespace App\Services;

use App\Contracts\UserProfileContract;
use Carbon\Carbon;

class UserProfileService
{
    protected $userProfileRepository;
    protected $userService;


    public function __construct(UserProfileContract $userProfileRepository, UserService $userService)
    {
        $this->userProfileRepository = $userProfileRepository;
        $this->userService = $userService;
    }

    public function all()
    {
        return $this->userProfileRepository->all();
    }

    public function find($id)
    {
        return $this->userProfileRepository->find($id);
    }

    public function getPaginatedProfiles($start, $count, $filter, $sortBy, $descending)
    {
        return $this->userProfileRepository->getPaginatedProfiles($start, $count, $filter, $sortBy, $descending);
    }

    public function create(array $data)
    {
        $user = $this->userService->findByField(
            [
                'search' => $data['phone'],
                'field' => 'phone',
                'fuzzy' => false
            ]
        );
        if ($user->isEmpty()) {
            $user = $this->userService->createUser(
                [
                    'phone' => $data['phone'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                ]
            );
        } else {
            $user = $user->first();
        }
        $data['user_id'] = $user->id;

        if (isset($data['medical_attachment_path'])) {
            $data['medical_attachment_path'] = json_encode($data['medical_attachment_path']);
        }
        $birthDate = $data['date_of_birth'] ?? null;
        if ($birthDate) {
            $data['date_of_birth'] = Carbon::createFromFormat('d/m/Y', $birthDate);
        }
        return $this->userProfileRepository->create($data);
    }

    public function getProfileByUserId($userId)
    {
        $user = $this->userService->getUserById($userId);
        if ($user && $user->userProfile) {
            $profile = $user->userProfile;
            return $this->userProfileRepository->find($profile->id);
        } else {
            return [];
        }
    }

    public function update($id, array $data)
    {
        $birthDate = $data['date_of_birth'] ?? null;
        if ($birthDate) {
            $data['date_of_birth'] = Carbon::createFromFormat('d/m/Y', $birthDate);
        }
        return $this->userProfileRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->userProfileRepository->delete($id);
    }
}
