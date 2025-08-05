<?php

namespace App\Services;

use App\Contracts\PrintLogContract;

class PrintLogService
{
    protected $printLogRepository;

    public function __construct(PrintLogContract $printLogRepository)
    {
        $this->printLogRepository = $printLogRepository;
    }

    public function getAllPrintLogs()
    {
        return $this->printLogRepository->getAll();
    }

    public function getPrintLogById($id)
    {
        return $this->printLogRepository->findById($id);
    }

    public function createPrintLog(array $data)
    {
        return $this->printLogRepository->create($data);
    }

    public function updatePrintLog($id, array $data)
    {
        return $this->printLogRepository->update($id, $data);
    }

    public function deletePrintLog($id)
    {
        return $this->printLogRepository->delete($id);
    }

    public function getByPrinterId($printerId)
    {
        return $this->printLogRepository->findByPrinterId($printerId);
    }
}
