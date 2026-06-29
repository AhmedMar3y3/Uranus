<?php

namespace App\Features\Auth\Requests;

use App\Http\Requests\BaseRequest;

class VerifyOtpRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc', 'max:255'],
            'otp' => ['required', 'digits:6'],
        ];
    }
}
