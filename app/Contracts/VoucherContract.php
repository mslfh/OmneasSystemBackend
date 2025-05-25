<?php

namespace App\Contracts;

interface VoucherContract
{
    public function getAllVouchers();
    public function getPaginatedVouchers($start = 0, $count = 10, $filter = null, $sortBy = 'id', $descending = false);
    public function getVoucherById($id);
    public function createVoucher(array $data);
    public function updateVoucher($id, array $data);
    public function deleteVoucher($id);
    public function findByCode($code);
}
