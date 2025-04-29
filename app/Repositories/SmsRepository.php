<?php

namespace App\Repositories;

use App\Contracts\SmsContract;
use Illuminate\Support\Facades\Http;

class SmsRepository implements SmsContract
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.sms.api_url'); // Ensure this is set in your config/services.php
        $this->apiKey = config('services.sms.api_key'); // Ensure this is set in your config/services.php
    }

    public function verifyToken()
    {
        // TODO: Implement verifyToken() method If needed
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get("{$this->apiUrl}/user/token/verify");

        return $response->json();
    }

    public function sendSms($text, $phone_number)
    {
        $url = "{$this->apiUrl}/send-sms";
        $fields = [
            'sms_text' => $text, // SMS text
            'numbers' => [$phone_number] // Numbers array
        ];
        $headers = [
            "APPKEY: {$this->apiKey}",
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        $ch = curl_init(); // Open connection
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Enable SSL verification
        curl_setopt($ch, CURLOPT_CAINFO, storage_path('certificates/cacert.pem')); // Path to the CA certificate file

        $result = curl_exec($ch);

        if (!$result) {
            $response_error = curl_error($ch);
            curl_close($ch);
            return json_encode([
                "status" => 400,
                "msg" => "Something went wrong, please try again",
                "result" => $response_error
            ]);
        }

        curl_close($ch);
        return json_encode([
            "status" => 200,
            "msg" => "SMS sent successfully",
            "result" => json_decode($result)
        ]);
    }

}
