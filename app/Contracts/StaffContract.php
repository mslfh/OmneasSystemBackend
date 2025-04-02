<?php

namespace App\Contracts;

interface StaffContract
{
    public function getAll();

    public function getAvailableStaffFromScheduletime( $dateTime);
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
