<?php

namespace App\Features\Admin\Requests;

use App\Http\Requests\BaseRequest;

class UpdateSettingsRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'android_latest_version'      => ['required', 'string', 'max:50'],
            'android_latest_version_code' => ['required', 'integer', 'min:1', 'max:2147483647'],
            'android_force_update'        => ['nullable', 'boolean'],
            'android_update_message'      => ['nullable', 'string', 'max:500'],
        ];
    }
}
