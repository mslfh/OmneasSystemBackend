<?php

namespace App\Services;

use App\Contracts\VoucherContract;

class VoucherService
{
    protected $voucherRepository;

    public function __construct(VoucherContract $voucherRepository)
    {
        $this->voucherRepository = $voucherRepository;
    }

    public function getAllVouchers()
    {
        return $this->voucherRepository->getAllVouchers();
    }

    public function getPaginatedVouchers($start = 0, $count = 10, $filter = null, $sortBy = 'id', $descending = false)
    {
        return $this->voucherRepository->getPaginatedVouchers($start, $count, $filter, $sortBy, $descending);
    }

    public function getVoucherById($id)
    {
        return $this->voucherRepository->getVoucherById($id);
    }

    public function generateVoucherCode($length = 8)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, 25)];
        }
        if($this->voucherRepository->findByCode($code)) {
            return $this->generateVoucherCode($length);
        }
        else{
            return $code;
        }
    }

    public function createVoucher(array $data)
    {
        if (empty($data['code'])) {
            $data['code'] = $this->generateVoucherCode();
        }
        if (empty($data['remaining_amount'])) {
            $data['remaining_amount'] = $data['amount'];
        }

        return $this->voucherRepository->createVoucher($data);
    }

    public function bulkCreateVoucher(array $data)
    {
        $codes = [];
        if (empty($data['codes'])) {
            for ($i = 0; $i < $data['count']; $i++) {
                $codes[] = $this->generateVoucherCode();
            }
        } else {
            $codes = explode(',', $data['codes']);
        }
        unset($data['codes']);
        for ($i = 0; $i < $data['count']; $i++) {
            $data['code'] = $codes[$i];
            if (empty($data['remaining_amount'])) {
                $data['remaining_amount'] = $data['amount'];
            }
            $this->voucherRepository->createVoucher($data);
        }
        return [
            'status' => 'success',
            'message' => 'Vouchers created successfully',
            'codes' => $codes
        ];
    }

    public function updateVoucher($id, array $data)
    {
        return $this->voucherRepository->updateVoucher($id, $data);
    }

    public function verifyVoucher($code)
    {
        $voucher = $this->voucherRepository->findByCode($code);
        if (!$voucher || $voucher->is_active !== 1) {
            return [
                'status' => 'error',
                'message' => "Voucher code {$code} is invalid."

            ];
        }  else {
            return [
                'status' => 'success',
                'message' => 'Voucher is valid',
                'data' => $voucher
            ];
        }
    }

    public function verifyValidCode($codes)
    {
        foreach ($codes as $code) {
            $voucher = $this->voucherRepository->findByCode($code);
            if ($voucher) {
                return [
                    'status' => 'error',
                    'message' => "Voucher code {$code} is invalid."
                ];
            }
        }

        return [
            'status' => 'success',
            'message' => 'All vouchers are valid',
            'data' => $codes
        ];
    }

    public function deleteVoucher($id)
    {
        return $this->voucherRepository->deleteVoucher($id);
    }

    public function findByCode($code)
    {
        return $this->voucherRepository->findByCode($code);
    }

}
