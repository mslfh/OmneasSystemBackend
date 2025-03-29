<?php

namespace App\Services;

use App\Contracts\PackageContract;

class PackageService
{
    protected $packageRepository;

    public function __construct(PackageContract $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function getAllPackages()
    {
        return $this->packageRepository->getAll();
    }

    public function getPackageWithService()
    {
        return $this->packageRepository->getPackageWithService();
    }

    public function getPackageById($id)
    {
        return $this->packageRepository->findById($id);
    }

    public function createPackage(array $data)
    {
        return $this->packageRepository->create($data);
    }

    public function updatePackage($id, array $data)
    {
        return $this->packageRepository->update($id, $data);
    }

    public function deletePackage($id)
    {
        $this->packageRepository->delete($id);
    }
}
