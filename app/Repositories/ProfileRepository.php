<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Contracts\ProfileContract;

class ProfileRepository implements ProfileContract
{
    protected $model;

    public function __construct(Profile $profile)
    {
        $this->model = $profile;
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
        $profile = $this->findById($id);
        if ($profile) {
            $profile->update($data);
            return $profile;
        }
        return null;
    }

    public function delete($id)
    {
        $profile = $this->findById($id);
        if ($profile) {
            return $profile->delete();
        }
        return false;
    }
}
