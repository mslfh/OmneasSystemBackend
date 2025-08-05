<?php

namespace App\Services;

use App\Contracts\ProfileContract;
use Illuminate\Http\JsonResponse;

class ProfileService
{
    protected $profileRepository;

    public function __construct(ProfileContract $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function getAll()
    {
        return $this->profileRepository->getAll();
    }

    public function findById($id)
    {
        return $this->profileRepository->findById($id);
    }

    public function create(array $data)
    {
        return $this->profileRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->profileRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->profileRepository->delete($id);
    }
}
