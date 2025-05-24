<?php

namespace App\Services;

use App\Contracts\UserContract;
use App\Repositories\UserRepository;

class UserService implements UserContract
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }

    public function getUserById($id)
    {
        return $this->userRepository->findById($id);
    }

    public function fetchByKey($data)
    {
        $field = $data['search'];
        return $this->userRepository->fetchByKeyword($field);
    }

     public function findByField($data)
    {
        $search = $data['search'];
        $field = $data['field'] ?? 'phone';
        return $this->userRepository->findByField($search,$field);
    }

    public function createUser(array $data)
    {
        $data['password'] = bcrypt($data['password']??$data['phone']);
        return $this->userRepository->create($data);
    }

    public function updateUser($id, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return $this->userRepository->update($id, $data);
    }

    public function getPaginatedUsers($start, $count, $filter, $sortBy, $descending)
    {
        return $this->userRepository->getPaginatedUsers($start, $count, $filter, $sortBy, $descending);
    }

    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
    }
}
