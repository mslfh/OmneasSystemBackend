<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAll()
    {
        return User::limit(200)->get();
    }

    public function findByField($field)
    {
        return User::where('name', 'like', '%' . $field . '%')
            ->orWhere('phone', 'like', '%' . $field . '%')
            ->orWhere('email', 'like', '%' . $field . '%')
            ->get();
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->findById($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = $this->findById($id);
        return $user->delete();
    }
}
