<?php
namespace App\Contracts;

interface PackageContract
{
    public function getAll();
    public function getPackageWithService();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
