<?php

namespace App\Repositories;

use App\Models\Voucher;
use App\Contracts\VoucherContract;

class VoucherRepository implements VoucherContract
{
    public function getAllVouchers()
    {
        return Voucher::all();
    }

    public function getPaginatedVouchers($start = 0, $count = 10, $filter = null, $sortBy = 'id', $descending = false)
    {
        $query = Voucher::query();

        if ($filter) {
            $query->where('code', 'like', '%' . $filter . '%');
        }

        if ($descending) {
            $query->orderByDesc($sortBy);
        } else {
            $query->orderBy($sortBy);
        }
        $total = $query->count();
        $data = $query->skip($start)->take($count) ->get();
        return [
            'data' => $data,
            'total' => $total,
        ];
    }

    public function getVoucherById($id)
    {
        return Voucher::findOrFail($id);
    }

    public function createVoucher(array $data)
    {
        return Voucher::create($data);
    }

    public function updateVoucher($id, array $data)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->update($data);
        return $voucher;
    }

    public function deleteVoucher($id)
    {
        $voucher = Voucher::findOrFail($id);
        return $voucher->delete();
    }

    public function findByCode($code)
    {
        return Voucher::where('code', $code)->first();
    }
}
