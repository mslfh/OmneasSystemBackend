<?php

namespace App\Services;

use App\Contracts\ServiceContract;

class ServiceService
{
    protected $serviceRepository;

    public function __construct(ServiceContract $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function index()
    {
        return $this->serviceRepository->index();
    }

    public function store($request)
    {
        return $this->serviceRepository->store($request);
    }

    public function show($id)
    {
        return $this->serviceRepository->show($id);
    }

    public function update($request, $id)
    {
        return $this->serviceRepository->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->serviceRepository->destroy($id);
    }

    public function getServiceByPackage($packageId)
    {
        return $this->serviceRepository->getServiceByPackage($packageId);
    }
}
