<?php

namespace LotousOrganization\LaravelLogin\app\Http\Controllers\Auth;

use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use LotousOrganization\LaravelLogin\app\Models\Otp;
use LotousOrganization\LaravelLogin\app\Models\User;
use LotousOrganization\LaravelLogin\app\Http\Services\Response;
use LotousOrganization\LaravelLogin\app\Http\Constants\Constants;
use LotousOrganization\LaravelLogin\app\Http\Requests\Auth\SignupRequest;
use LotousOrganization\LaravelLogin\app\Http\Requests\Auth\CredentialsRequest;

class SignupController extends Controller
{
    public function signup(SignupRequest $request)
    {
        $validated = $request->validated();

        $otpRecord = Otp::where('username', $validated['phone'])
            ->where('type', 'Login')
            ->latest()
            ->first();

        if( ! $otpRecord || $otpRecord->token != $validated['otp']){
            return Response::error(Constants::ERROR_LOGIN_OTP,422);
        }

        $user = User::create([
            "phone"      => $validated['phone'],
            "name"       => $validated['first_name'],
            "lastname"   => $validated['last_name'],
            "password"   => $validated['password'],
            "email"      => $validated['email'],
        ]);

        $user['token'] = $user->createToken('Laravel Password Grant Client')->accessToken;
        return Response::success("", $user);
        
    }
}
