<?php

namespace App\Repositories;

use App\Models\ProductProfile;
use App\Contracts\ProductProfileContract;

class ProductProfileRepository implements ProductProfileContract
{
    protected $model;

    public function __construct(ProductProfile $productProfile)
    {
        $this->model = $productProfile;
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
        $productProfile = $this->findById($id);
        if ($productProfile) {
            $productProfile->update($data);
            return $productProfile;
        }
        return null;
    }

    public function delete($id)
    {
        $productProfile = $this->findById($id);
        if ($productProfile) {
            return $productProfile->delete();
        }
        return false;
    }
}
