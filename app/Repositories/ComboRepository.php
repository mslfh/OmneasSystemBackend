<?php

namespace App\Repositories;

use App\Models\Combo;
use App\Contracts\ComboContract;

class ComboRepository implements ComboContract
{
    protected $model;

    public function __construct(Combo $combo)
    {
        $this->model = $combo;
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
        $combo = $this->findById($id);
        if ($combo) {
            $combo->update($data);
            return $combo;
        }
        return null;
    }

    public function delete($id)
    {
        $combo = $this->findById($id);
        if ($combo) {
            return $combo->delete();
        }
        return false;
    }

    public function getActiveItems()
    {
        return $this->model->where('is_active', true)->get();
    }
}
