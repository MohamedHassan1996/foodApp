<?php

namespace App\Services\Otp;

use Fouladgar\OTP\Contracts\SMSClient;
use Fouladgar\OTP\Notifications\Messages\MessagePayload;
use Twilio\Rest\Client;

class OtpSMSService implements SMSClient
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function sendMessage(MessagePayload $payload): mixed
    {
        return $this->twilio->messages->create($payload->to(), [
            'from' => env('TWILIO_FROM'),
            'body' => $payload->content(),
        ]);
    }
}
