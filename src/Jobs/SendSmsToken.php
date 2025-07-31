<?php

namespace LotousOrganization\LaravelLogin\Jobs;

use App\Services\KavenegarService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSmsToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $phoneNumber;
    protected string $otp;

    public function __construct(string $phoneNumber, string $otp)
    {
        $this->phoneNumber = $phoneNumber;
        $this->otp = $otp;
    }

    public function handle(KavenegarService $kavenegarService): void
    {
        $template = env("KAVENEGAR_TEMPLATE");

        $response = $kavenegarService->sendOtp(
            $this->phoneNumber,
            $this->otp,
            $template
        );

        if (!$response) {
            Log::warning("Failed to send OTP to {$this->phoneNumber}");
        }
    }
}
