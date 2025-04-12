<?php

namespace App\Services;

use App\Contracts\SystemSettingContract;

class SystemSettingService
{
    protected $repository;

    public function __construct(SystemSettingContract $repository)
    {
        $this->repository = $repository;
    }

    public function getAllSettings()
    {
        return $this->repository->getAll();
    }

    public function getSettingById($id)
    {
        return $this->repository->findById($id);
    }

    public function getSettingByKey($key)
    {
        return $this->repository->getByKey($key);
    }

    public function createSetting(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateSetting($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteSetting($id)
    {
        return $this->repository->delete($id);
    }
}
