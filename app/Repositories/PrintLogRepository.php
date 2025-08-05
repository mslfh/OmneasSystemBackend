<?php

namespace App\Repositories;

use App\Models\PrintLog;
use App\Contracts\PrintLogContract;

class PrintLogRepository implements PrintLogContract
{
    protected $model;

    public function __construct(PrintLog $printLog)
    {
        $this->model = $printLog;
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
        $printLog = $this->findById($id);
        if ($printLog) {
            $printLog->update($data);
            return $printLog;
        }
        return null;
    }

    public function delete($id)
    {
        $printLog = $this->findById($id);
        if ($printLog) {
            return $printLog->delete();
        }
        return false;
    }

    public function findByPrinterId($printerId)
    {
        return $this->model->where('printer_id', $printerId)->get();
    }
}
