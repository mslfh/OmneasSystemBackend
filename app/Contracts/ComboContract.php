<?php

namespace App\Contracts;

interface ComboContract
{
    public function findAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getActiveItems();
}
