<?php

namespace App\Features\Notifications\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreDeviceTokenRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'fcm_token' => ['required', 'string', 'max:4096'],
            'platform' => ['nullable', Rule::in(['ios', 'android', 'web'])],
            'device_name' => ['nullable', 'string', 'max:120'],
        ];
    }
}
