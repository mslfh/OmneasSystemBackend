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

    public function getPaginatedUsers($start, $count, $filter, $sortBy, $descending)
    {
        $query = User::query();
        $query->where('id', '!=', 1)
            ->where('id', '!=', 2)
            ->whereDoesntHave('staff')
            ->when($filter, function ($query) use ($filter) {
                return $query->where('name', 'like', '%' . $filter . '%')
                    ->orWhere('phone', 'like', '%' . $filter . '%')
                    ->orWhere('email', 'like', '%' . $filter . '%');
            })
            ->orderBy($sortBy, $descending ? 'desc' : 'asc');
        $total = $query->count();
        $data = $query->skip($start)->take($count) ->get();
        return [
            'data' => $data,
            'total' => $total,
        ];
    }

    public function findByField($field)
    {
        return User::where('name', 'like', '%' . $field . '%')
            ->orWhere('phone', 'like', '%' . $field . '%')
            ->orWhere('email', 'like', '%' . $field . '%')
            ->limit(20)
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
