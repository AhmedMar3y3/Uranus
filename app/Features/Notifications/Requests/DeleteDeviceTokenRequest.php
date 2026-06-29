<?php

namespace App\Features\Notifications\Requests;

use App\Http\Requests\BaseRequest;

class DeleteDeviceTokenRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'fcm_token' => ['required', 'string', 'max:4096'],
        ];
    }
}
