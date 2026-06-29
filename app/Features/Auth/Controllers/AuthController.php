<?php

namespace App\Features\Auth\Controllers;

use App\Enums\StatusCodesEnum;
use App\Features\Auth\Requests\SendOtpRequest;
use App\Features\Auth\Requests\VerifyOtpRequest;
use App\Features\Auth\Resources\AuthTokenResource;
use App\Features\Auth\Services\AuthService;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiController
{
    public function __construct(private readonly AuthService $auth)
    {
    }

    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        $this->auth->sendOtp($request->validated('email'), $request->validated());

        return $this->resetResponse()
            ->setSuccess(StatusCodesEnum::SUCCESS->value, __('messages.code_sent_successfully'))
            ->response();
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $authData = $this->auth->verifyOtp(
            $request->validated('email'),
            $request->validated('otp')
        );

        return $this->resetResponse()
            ->setSuccess(StatusCodesEnum::SUCCESS->value, __('messages.login_successful'))
            ->addResponseArray((new AuthTokenResource($authData))->toArray($request))
            ->response();
    }
}
