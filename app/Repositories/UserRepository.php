<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAll()
    {
        // Get all users with a limit of 200
        return User::where('id', '!=', 1)
            ->where('id', '!=', 2)
            ->whereDoesntHave('staff')
            ->limit(200)->get();
    }

    public function findByField($field)
    {
        return User::where('name', 'like', '%' . $field . '%')
            ->orWhere('phone', 'like', '%' . $field . '%')
            ->orWhere('email', 'like', '%' . $field . '%')
            ->limit(50)
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
