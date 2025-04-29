<?php

namespace App\Services;

use App\Contracts\SmsContract;

class SmsService
{
    protected $smsRepository;

    public function __construct(SmsContract $smsRepository)
    {
        $this->smsRepository = $smsRepository;
    }

    public function verifyToken()
    {
        return $this->smsRepository->verifyToken();
    }

    public function sendSms(string $message,string $phone_number)
    {
        return $this->smsRepository->sendSms($message, $phone_number);
    }
}
