<?php

namespace App\Repositories;

use App\Models\ComboProduct;
use App\Contracts\ComboProductContract;

class ComboProductRepository implements ComboProductContract
{
    protected $model;

    public function __construct(ComboProduct $comboProduct)
    {
        $this->model = $comboProduct;
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
        $comboProduct = $this->findById($id);
        if ($comboProduct) {
            $comboProduct->update($data);
            return $comboProduct;
        }
        return null;
    }

    public function delete($id)
    {
        $comboProduct = $this->findById($id);
        if ($comboProduct) {
            return $comboProduct->delete();
        }
        return false;
    }

    public function findByComboId($comboId)
    {
        return $this->model->where('combo_id', $comboId)->get();
    }
}
