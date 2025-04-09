<?php

namespace App\Contracts;

interface UserContract
{
    public function getAllUsers();
    public function getUserById($id);
    public function findByField($field);
    public function createUser(array $data);
    public function updateUser($id, array $data);
    public function deleteUser($id);
}
