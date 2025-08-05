<?php

namespace App\Services;

use App\Contracts\PrinterContract;

class PrinterService
{
    protected $printerRepository;

    public function __construct(PrinterContract $printerRepository)
    {
        $this->printerRepository = $printerRepository;
    }

    public function getAllPrinters()
    {
        return $this->printerRepository->getAll();
    }

    public function getPrinterById($id)
    {
        return $this->printerRepository->findById($id);
    }

    public function createPrinter(array $data)
    {
        return $this->printerRepository->create($data);
    }

    public function updatePrinter($id, array $data)
    {
        return $this->printerRepository->update($id, $data);
    }

    public function deletePrinter($id)
    {
        return $this->printerRepository->delete($id);
    }

    public function getActivePrinters()
    {
        return $this->printerRepository->getActivePrinters();
    }
}
