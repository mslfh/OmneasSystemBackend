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

    public function create(array $data)
    {
        if(isset($data['medical_attachment_path'])){
            $data['medical_attachment_path'] = json_encode($data['medical_attachment_path']);
        }
        $birthDate = $data['date_of_birth'] ?? null;
        if ($birthDate) {
            $data['date_of_birth'] = Carbon::createFromFormat('d/m/Y', $birthDate);
        }
        return $this->userProfileRepository->create($data);
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
