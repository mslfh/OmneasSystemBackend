<?php

namespace App\Repositories;

use App\Models\Printer;
use App\Contracts\PrinterContract;

class PrinterRepository implements PrinterContract
{
    protected $model;

    public function __construct(Printer $printer)
    {
        $this->model = $printer;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $printer = $this->findById($id);
        if ($printer) {
            $printer->update($data);
            return $printer;
        }
        return null;
    }

    public function delete($id)
    {
        $printer = $this->findById($id);
        if ($printer) {
            return $printer->delete();
        }
        return false;
    }

    public function getActivePrinters()
    {
        return $this->model->where('is_active', true)->get();
    }
}
