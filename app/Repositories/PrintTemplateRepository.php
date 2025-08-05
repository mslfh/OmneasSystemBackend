<?php

namespace App\Repositories;

use App\Models\PrintTemplate;
use App\Contracts\PrintTemplateContract;

class PrintTemplateRepository implements PrintTemplateContract
{
    protected $model;

    public function __construct(PrintTemplate $printTemplate)
    {
        $this->model = $printTemplate;
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
        $printTemplate = $this->findById($id);
        if ($printTemplate) {
            $printTemplate->update($data);
            return $printTemplate;
        }
        return null;
    }

    public function delete($id)
    {
        $printTemplate = $this->findById($id);
        if ($printTemplate) {
            return $printTemplate->delete();
        }
        return false;
    }

    public function getActiveTemplates()
    {
        return $this->model->where('is_active', true)->get();
    }
}
