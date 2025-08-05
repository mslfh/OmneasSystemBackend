<?php

namespace App\Repositories;

use App\Models\ComboItem;
use App\Contracts\ComboItemContract;

class ComboItemRepository implements ComboItemContract
{
    protected $model;

    public function __construct(ComboItem $comboItem)
    {
        $this->model = $comboItem;
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
        $comboItem = $this->findById($id);
        if ($comboItem) {
            $comboItem->update($data);
            return $comboItem;
        }
        return null;
    }

    public function delete($id)
    {
        $comboItem = $this->findById($id);
        if ($comboItem) {
            return $comboItem->delete();
        }
        return false;
    }

    public function findByComboId($comboId)
    {
        return $this->model->where('combo_id', $comboId)->get();
    }
}
