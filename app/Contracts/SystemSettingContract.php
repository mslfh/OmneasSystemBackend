<?php

namespace App\Contracts;

interface SystemSettingContract
{
    public function getAll();
    public function findById($id);
    public function getByKey($key);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
