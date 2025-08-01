<?php

namespace LotousOrganization\LaravelLogin\app\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Sentry\SentrySdk;

class KavenegarService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = env("KAVENEGAR_API_KEY");
    }

    public function sendOtp(string $phoneNumber, string $otp, string $template): ?array
    {
        $url = "https://api.kavenegar.com/v1/{$this->apiKey}/verify/lookup.json";

        $response = Http::timeout(20)->get($url, [
            'receptor' => $phoneNumber,
            'token'    => $otp,
            'template' => $template,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        $this->reportError($response);
        return null;
    }

    protected function reportError($response): void
    {
        $error = $response->json();
        Log::error('Kavenegar API Error', [
            'status' => $response->status(),
            'response' => $error,
        ]);

        SentrySdk::init();
        SentrySdk::getCurrentHub()->captureMessage("Kavenegar API Error: " . json_encode($error));
    }
}
