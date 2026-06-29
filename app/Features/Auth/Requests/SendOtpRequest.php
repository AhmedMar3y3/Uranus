<?php

namespace App\Features\Auth\Requests;

use App\Http\Requests\BaseRequest;

class SendOtpRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc', 'max:255'],
        ];
    }
}
