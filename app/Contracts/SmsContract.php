<?php

namespace App\Contracts;

interface SmsContract
{
    public function verifyToken();

    public function sendSms(string $message,string $phone_number);
}
